<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\RedactaUser;
use App\Models\Issuer;
use App\Models\Anexo;
use App\Models\DocumentType;
use App\Models\Heading;
use App\Models\Signature;
use App\Models\DocumentSharedAccess;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;



class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\StoreDocumentRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDocumentRequest $request)
    {
        $data = $request->validated();
        if (!isset($data['true_copy_stamp_id']) && isset($data['issuer_id'])) {
            $issuerSettings = Issuer::find($data['issuer_id'])->issuerSettings;
            if (isset($issuerSettings->suggestedTrueCopyStamp)) {
                $data['true_copy_stamp_id'] = $issuerSettings->suggestedTrueCopyStamp->id;
            }
        }
        if (isset($data['stamps'])) {
            $data['stamps'] = json_encode( $data['stamps']);
        }
        $data['redacta_user_id'] = $request->user()->id;
        $document = new Document();
        $document->set($data);
        $document->save();
        $document->anexos = Anexo::with(['file'])->where('document_id', $document->id)->orderBy('index', 'ASC')->get();
        $document->signatures = Signature::with(['stamp'])->where('document_id', $document->id)->get();
        $document->body = json_decode($document->body);
        if ($document->stamps) {
            $document->stamps = json_decode($document->stamps);
        } else {
            $document->stamps = [];
        }
        return response()->json([
            'status' => 201,
            'message' => 'OK',
            'data' => $document        
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $document = Document::find($id);
        if (!$document) {
            return response()->json([
                'status' => 404,
                'message' => 'Recurso inexistente'        
            ], 404);
        }
        $loggedInUserId = $request->user()->id;
        $this->authorize('view', $document);              
        if ($request->accepts(['application/pdf'])) {                
            $isCopy = $request->boolean('is_copy', false);
            $blankPageAtEnd = $request->boolean('blank_page_at_end', false);
            $filename = 'documento';
            $filename = $document->name;
            if($isCopy){
                $filename = $filename.'_copia';
            }
            return response($this->generatePDF($document, $isCopy, $loggedInUserId, $blankPageAtEnd))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="'.$filename.'.pdf"; filename*="'.$filename.'.pdf"')
                ->header('Access-Control-Expose-Headers', 'Content-Disposition');
        } else if ($request->accepts(['application/json'])) {
            $document->load('documentSharedAccesses');
            $document->anexos = Anexo::with(['file'])->where('document_id', $document->id)->orderBy('index', 'ASC')->get();
            $document->signatures = Signature::with(['stamp'])->where('document_id', $document->id)->get();
            $document->body = json_decode($document->body);
            if ($document->stamps) {
                $document->stamps = json_decode($document->stamps);
            } else {
                $document->stamps = [];
            }
            return response()->json([
                'status' => 200,
                'message' => 'OK',
                'data' => $document            
            ]);  
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\UpdateDocumentRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function update(UpdateDocumentRequest $request, $id)
    {
        $data = $request->validated();
        $document = Document::find($id);
        if (!$document) {
            return response()->json([
                'status' => 404,
                'message' => 'Recurso inexistente'        
            ], 404);
        }
        $this->authorize('update', $document);
        if (isset($data['body'] )) {
            $data['body'] = json_encode($data['body']);
        }
        if (isset($data['stamps'])) {
            $data['stamps'] = json_encode( $data['stamps']);
        }
        if (isset($data['issuer_id']) && !$document->true_copy_stamp_id) {
            $issuerSettings = Issuer::find($data['issuer_id'])->issuerSettings;
            if (isset($issuerSettings->suggestedTrueCopyStamp)) {
                $data['true_copy_stamp_id'] = $issuerSettings->suggestedTrueCopyStamp->id;
            }
        }
        $document->update($data);
        $document->anexos = Anexo::with(['file'])->where('document_id', $document->id)->orderBy('index', 'ASC')->get();
        $document->signatures = Signature::with(['stamp'])->where('document_id', $document->id)->get();
        $document->body = json_decode($document->body);
        if ($document->stamps) {
            $document->stamps = json_decode($document->stamps);
        } else {
            $document->stamps = [];
        }
        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => $document           
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $document = Document::find($id);
        if (!$document) {
            return response()->json([
                'status' => 404,
                'message' => 'Recurso inexistente'        
            ], 404);
        }
        $this->authorize('delete', $document);
        $document->delete();
        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => $document           
        ]);
    }

     /**
     * Sets the visibility level of a given document.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setVisibilityLevel(Request $request, $id) {
        $document = Document::find($id);
        if (!$document) {
            return response()->json([
                'status' => 404,
                'message' => 'Recurso inexistente'        
            ], 404);
        }
        $this->authorize('update', $document);
        $validator = Validator::make($request->all(), [
            'visibility_level_id' => 'required|numeric|exists:visibility_levels,id'
        ], [
            'required' => 'El campo "nivel de visibilidad" es requerido',
            'numeric' => 'El campo "nivel de visibilidad" debe ser un número',
            'exists' => 'El valor ingresado para "nivel de visibilidad" no es válido'
        ])->stopOnFirstFailure(true);
        $validator->validate();
        $document->visibility_level_id = $request->input('visibility_level_id');
        $document->save();
    }

    public function generatePDF($document, $isCopy, $loggedInUserId, $blankPageAtEnd){      
        $html = view($document->documentType->view)->with([
            'document' => $document, 
            'isCopy' => $isCopy, 
            'anexos' => Anexo::with(['file'])->where('document_id', $document->id)->orderBy('index', 'ASC')->get(),
            'blankPageAtEnd' => $blankPageAtEnd
        ]);
        $snappdf = new \Beganovich\Snappdf\Snappdf();
        $pdf = $snappdf
            ->setHtml($html->render())
            ->waitBeforePrinting(10000) 
            ->generate();
        return $pdf;
    }

    public function search(Request $request){        
        $params = [
            'keywords',
            'document_type_id',
            'name',
            'number',
            'issuer_id',
            'issue_place',
            'subject',
            'destinatary'
        ];
        $searchInput = [];
        $output = [];

        $isCopy = $request->boolean('is_copy', false);
        $query =  Document::with(['issuer','documentType']);
        if ($request->boolean('shared', false)) {
            // Get ids from documents shared with the currrent user
            $singleUserSharedDocumentQuery = DocumentSharedAccess::where('document_shared_accessable_type', 'App\\Models\\RedactaUser')
                ->where('document_shared_accessable_id', $request->user()->id);

            // Get ids from documents shared with groups which current user belongs to
            $userGroupIds = $request->user()->groups()->pluck('group_id')->toArray();
            $groupSharedDocumentQuery = DocumentSharedAccess::where('document_shared_accessable_type', 'App\\Models\\Group')
                ->whereIn('document_shared_accessable_id', $userGroupIds);
            
            if ($request->query('shared_by', '*') != '*') {
                $singleUserSharedDocumentQuery->where('redacta_user_id', $request->query('shared_by'));
                $groupSharedDocumentQuery->where('redacta_user_id', $request->query('shared_by'));
            }
            $singleUserSharedDocumentIds = $singleUserSharedDocumentQuery->pluck('document_id')->toArray();
            $groupSharedDocumentIds = $groupSharedDocumentQuery->pluck('document_id')->toArray();

            // Merge both arrays and remove duplicates
            $documentsId = array_unique(array_merge($singleUserSharedDocumentIds, $groupSharedDocumentIds));

            $query = Document::whereIn('id', $documentsId)
                ->where('redacta_user_id', '<>', $request->user()->id);
        } else {
            $query = Document::where('redacta_user_id', $request->user()->id);
        }
        foreach ($params as $param) {
            if ($request->has($param)) { 
                if (in_array($param, ['name', 'destinatary', 'subject'])){
                    array_push($searchInput, [$param, 'LIKE', '%'.$request->query($param).'%']);
                } else if ($param == 'keywords'){
                    array_push($searchInput, ['body', 'REGEXP', preg_replace('/\s+/', '|', $request->query($param))]);
                } else {
                    array_push($searchInput, [$param, '=', $request->query($param)]);
                }
            }
        }
        $query = $query->where($searchInput);
        if($request->has('issue_date_start')){
            $query = $query->whereDate('issue_date', '>=', $request->query('issue_date_start'));
        }
        if($request->has('issue_date_end')){
            $query = $query->whereDate('issue_date', '<=', $request->query('issue_date_end'));
        }
        $results = $query->orderBy('updated_at', 'desc')->get();
        foreach ($results as $document) {
            $data = [
                'id' => $document->id,
                'issuer' => $document->issuer? $document->issuer->description : 'Sin definir',
                'documentType' => $document->documentType->description,
                'name' => $document->name,
                'issueDate' => $document->issue_date ? date('d-m-Y', strtotime($document->issue_date)) : '',
                'number' => $document->number,
                'updated_at' => date('d-m-Y H:m:s', strtotime($document->updated_at)),
            ];
            if ($request->boolean('shared', false)) {
                $documentSharedAccesses = $document->documentSharedAccesses;
                if (!$documentSharedAccesses->isEmpty()) {
                    $accessCreator = $documentSharedAccesses->first()->redactaUser;
                    if ($accessCreator) {
                        $data['shared_by'] =  $accessCreator->name.' '.$accessCreator->last_name;
                    }
                }
            }
            array_push($output, $data);
        }
        return $output; 
    }
    
    public function exportAnexo(Request $request, $id) {
        $document = Document::where('id', $id)->first();
        $html = "";
        if($document) {
            $html = view($document->documentType->view)->with([
                'document' => $document, 
                'isCopy' => true, 
                'anexos' => Anexo::with(['file'])->where('document_id', $document->id)->orderBy('index', 'ASC')->get(),
                'blankPageAtEnd' =>  $request->boolean('blank_page_at_end', false)
            ]);
        } 
        return $html;
    }

   
}