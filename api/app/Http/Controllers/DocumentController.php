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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validateRequest($request);
        try {
            if(!isset($data['true_copy_stamp_id'])){
                $issuerSettings = Issuer::find($data['issuer_id'])->issuerSettings;
                $data['true_copy_stamp_id'] = $issuerSettings->suggestedTrueCopyStamp->id;
            }
            $data['redacta_user_id'] = $request->user()->id;
            $document = new Document();
            $document->set($data);
            $document->save();
            $document->anexos = Anexo::with(['file'])->where('document_id', $document->id)->get();
            $document->signatures = Signature::with(['stamp'])->where('document_id', $document->id)->get();
            $document->body = json_decode($document->body);
            return response()->json([
                'status' => 201,
                'message' => 'OK',
                'data' => $document        
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Error en el servidor. Reintente la operación'
            ], 500);
        }
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
        try {
            $document = Document::find($id);
            $loggedInUserId = $request->user()->id;
            if (!$document || !$this->userHasAccessToDocument($loggedInUserId, $document)) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Recurso inexistente'        
                ], 404);
            }                  
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
                $document->anexos = Anexo::with(['file'])->where('document_id', $document->id)->get();
                $document->signatures = Signature::with(['stamp'])->where('document_id', $document->id)->get();
                $document->body = json_decode($document->body);
                return response()->json([
                    'status' => 200,
                    'message' => $loggedInUserId,
                    'data' => $document            
                ]);  
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error en el servidor. Reintente la operación'
            ], 500);
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function update(Request $request, $id)
    {
        $data = $this->validateRequest($request);
        try {
            $document = Document::find($id);
            if (!$document || !$this->userHasAccessToDocument($request->user()->id, $document)) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Recurso inexistente'        
                ], 404);
            }  
            $document->set($data);
            $document->save();
            $document->anexos = Anexo::with(['file'])->where('document_id', $document->id)->get();
            $document->signatures = Signature::with(['stamp'])->where('document_id', $document->id)->get();
            $document->body = json_decode($document->body);
            return response()->json([
                'status' => 200,
                'message' => 'OK',
                'data' => $document           
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Error en el servidor. Reintente la operación'
            ], 500);
        }
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
        try { 
            $document = Document::find($id);
            if (!$document || $document->redactaUser->id != $loggedInUserId) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Recurso inexistente'        
                ], 404);
            }
            $document->delete();
            return response()->json([
                'status' => 200,
                'message' => 'OK',
                'data' => $document           
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Error en el servidor. Reintente la operación'
            ], 500);
        }
    }

    public function generatePDF($document, $isCopy, $loggedInUserId, $blankPageAtEnd){      
        $html = view($document->documentType->view)->with([
            'document' => $document, 
            'isCopy' => $isCopy, 
            'anexos' => Anexo::with(['file'])->where('document_id', $document->id)->get(),
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
        try {
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
                $documentsId = DocumentSharedAccess::where('redacta_user_id', $request->user()->id)->pluck('document_id')->all();
                $query = Document::whereIn('id', $documentsId);
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
            foreach ($results as $document){
                array_push($output, [
                    'id' => $document->id,
                    'issuer' => $document->issuer->description,
                    'documentType' => $document->documentType->description,
                    'name' => $document->name,
                    'issueDate' => date('d-m-Y', strtotime($document->issue_date)),
                    'number' => $document->number,
                    'updated_at' => date('d-m-Y H:m:s', strtotime($document->updated_at))
                ]);
            }
            return $output; 
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Error en el servidor. Reintente la operación'
            ], 500);
        }
    }

    private function validateRequest($request) {
        $validator = Validator::make($request->all(), [
                'document_type_id' => 'required|numeric',
                'name' => 'sometimes|string|nullable',
                'number' => 'sometimes|numeric|nullable',
                'issuer_id' => 'required|numeric|exists:redacta_users,id',
                'issue_date' => 'sometimes|date|nullable',
                'body' => 'required',
                'subject' => 'sometimes|nullable|string',
                'destinatary' => 'sometimes|nullable|string',
                'has_anexo_unico' => 'sometimes|boolean',
                'heading_id' => 'required|numeric',
                'operative_section_beginning_id' => 'required|numeric',
                'true_copy_stamp_id' => 'sometimes|numeric|nullable'
            ], [
                'required' => 'El campo :attribute es requerido',
                'numeric' => 'El campo :attribute debe ser un número',
                'date' => 'El campo :attribute debe ser una fecha en formato dd/mm/yyyy',
                'string' => 'El campo :attribute debe ser de tipo string',
                'boolean' => 'El campo :attribute debe ser de tipo booleano'
            ], [
                'document_type_id' => '"tipo de documento"',
                'name' => '"nombre de documento"',
                'number' => '"número"',
                'issuer_id' => '"dependencia emisora"',
                'issue_date' => '"fecha de emisión"',
                'subject' => '"Asunto"',
                'destinatary' => '"Destinatario"',
                'has_anexo_unico' => '"Tiene anexo único"',
                'heading_id' => '"Membrete"',
                'operative_section_beginning_id' => '"Inicio de sección operativa"',
                'true_copy_stamp_id' => '"Firmante de copia fiel"'
            ])->stopOnFirstFailure(true);
        $validator->validate();
        return $validator->validated();
    }

    private function userHasAccessToDocument($loggedInUserId, $document){
        if ($document->redactaUser->id != $loggedInUserId) {
            $documentSharedAccess = DocumentSharedAccess::where([
                ['redacta_user_id', '=', $loggedInUserId],
                ['document_id', '=', $document->id]
            ])->get();
            if (count($documentSharedAccess) == 0) {
                return false;
            }
        }
        return true; 
    }
    
    public function exportAnexo(Request $request, $id){
        $document = Document::where('id', $id)->first();
        $html = "";
        if($document) {
            $html = view($document->documentType->view)->with([
                'document' => $document, 
                'isCopy' => true, 
                'anexos' => Anexo::with(['file'])->where('document_id', $document->id)->get(),
                'blankPageAtEnd' =>  $request->boolean('blank_page_at_end', false)
            ]);
        } 
        return $html;
    }

   
}
