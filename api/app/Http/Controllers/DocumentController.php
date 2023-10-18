<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\RedactaUser;
use App\Models\Issuer;
use App\Models\Anexo;
use App\Models\DocumentType;
use App\Models\Heading;
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
            $issuerSettings = Issuer::find($data['issuer_id'])->issuerSettings;
            $data['true_copy_stamp_id'] = $issuerSettings->trueCopyStamp->id;
            if($data['ad_referendum'] && $issuerSettings->adReferendumOperativeSectionBeginning){
                $data['operative_section_beginning_id'] = $issuerSettings->adReferendumOperativeSectionBeginning->id;
            } else if ($issuerSettings->operativeSectionBeginning){
                $data['operative_section_beginning_id'] = $issuerSettings->operativeSectionBeginning->id;
            }
            $data['heading_id'] = $issuerSettings->heading->id;
            $document = new Document();
            $document->set($data);
            $document->save();
            return response()->json([
                'status' => 201,
                'message' => 'OK',
                'data' => $this->formatDocumentDataForRetrieving($document)        
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
            if(!$document || $document->redactaUser->id != $loggedInUserId){
                return response()->json([
                    'status' => 404,
                    'message' => 'El documento que desea modificar no existe'        
                ], 404);
            } 
            if ($request->accepts(['application/pdf'])) {                
                $isCopy = $request->boolean('is_copy', false);
                $filename = 'documento';
                $filename = $document->name;
                if($isCopy){
                    $filename = $filename.'_copia';
                }
                return response($this->generatePDF($document, $isCopy, $loggedInUserId))
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'attachment; filename="'.$filename.'.pdf"; filename*="'.$filename.'.pdf"')
                    ->header('Access-Control-Expose-Headers', 'Content-Disposition');
            } else if ($request->accepts(['application/json'])) {  
                return response()->json([
                    'status' => 200,
                    'message' => $loggedInUserId,
                    'data' => $this->formatDocumentDataForRetrieving($document)            
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
            $issuerSettings = Issuer::find($data['issuer_id'])->issuerSettings;
            $loggedInUserId = $request->user()->id;        
            $document = Document::where('id', $id)->first();
            if(!$document || $document->redactaUser->id != $loggedInUserId){
                return response()->json([
                    'status' => 404,
                    'message' => 'El documento que desea modificar no existe'        
                ], 404);  
            }  
            $document->set($data);
            $document->save();
            return response()->json([
                'status' => 200,
                'message' => 'OK',
                'data' => $this->formatDocumentDataForRetrieving($document)           
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function generatePDF($document, $isCopy, $loggedInUserId){      
        $heading = Heading::where([
            ['issuer_id', '=', $document->issuer_id],
            ['year', '=', date('Y', strtotime($document->issue_date))]
        ])->first();
        $html = view($document->documentType->view)->with([
            'document' => $document, 
            'isCopy' => $isCopy, 
            'anexos' => Anexo::with(['file'])->where('document_id', $document->id)->get(),
            'issuerSettings' => $document->issuer->issuerSettings,
            'headingFile' => $heading->file 
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
                'documentTypeId',
                'name',
                'number',
                'issuerId',
                'issuePlace',
                'adReferendum',
                'subject',
                'destinatary',
                'id'
            ];
            $searchInput = [];
            $output = [];
            foreach($params as $param){
                if($request->has($param)){
                    if (in_array($param, ['name', 'destinatary', 'subject'])){
                        array_push($searchInput, [$param, 'LIKE', '%'.$request->query($param).'%']);
                    } else if ($param != 'issueDateStart' && $param != 'issueDateEnd') {
                        array_push($searchInput, [Str::snake($param), '=', $request->query($param)]);
                    }
                }
            }
            array_push($searchInput, ['redacta_user_id', '=', $request->user()->id]);
            $results = Document::with(['issuer','documentType'])->where($searchInput);
            if($request->has('issueDateStart')){
                $results = $results->whereDate('issue_date', '>=', $request->query('issueDateStart'));
            }
            if($request->has('issueDateEnd')){
                $results = $results->whereDate('issue_date', '<=', $request->query('issueDateEnd'));
            }
            $results = $results->get();
            foreach ($results as $document){
                array_push($output, [
                    'id' => $document->id,
                    'issuer' => $document->issuer->description,
                    'documentType' => $document->documentType->description,
                    'name' => $document->name,
                    'issueDate' => $document->issue_date,
                    'number' => $document->number
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
                'name' => 'required',
                'number' => 'required|numeric',
                'issuer_id' => 'required|numeric',
                'issue_date' => 'required|date',
                'issue_place' => 'required',
                'ad_referendum' => 'boolean',
                'body' => 'required',
                'subject' => 'sometimes|nullable|string',
                'destinatary' => 'sometimes|nullable|string'
                //'anexosSectionTypeId' => 'required'
            ], [
                'required' => 'El campo :attribute es requerido',
                'numeric' => 'El campo :attribute debe ser un número',
                'date' => 'El campo :attribute debe ser una fecha en formato dd/mm/yyyy',
                'string' => 'El campo :attribute debe ser de tipo string',
            ], [
                'document_type_id' => '"tipo de documento"',
                'name' => '"nombre de documento"',
                'number' => '"número"',
                'issuer_id' => '"dependencia emisora"',
                'issue_date' => '"fecha de emisión"',
                'issue_place' => '"lugar de emisión"',
                'ad_referendum' => '"ad referendum"',
                'subject' => '"Asunto"',
                'destinatary' => '"Destinatario"',
                //'anexosSectionTypeId' => '"tipo de anexo"'
            ])->stopOnFirstFailure(true);
        $validator->validate();
        return $validator->validated();
    }

    /*private function formatDocumentDataForStoring($data){  
        return [
            'document_type_id' => $data->documentTypeId,
            'name' => $data->name,
            'number' => $data->number,
            'issuer_id' => $data->issuerId,
            'issue_date' => $data->issueDate,
            'issue_place' => $data->issuePlace,
            'ad_referendum' => $data->adReferendum,
            'subject' => $data->subject,
            'destinatary' => $data->destinatary,
            //'anexos_section_type_id' => $data->anexosSectionTypeId, 
            'body' => json_encode($data->body)
        ];
    }*/
    
    private function formatDocumentDataForRetrieving($data){  
        return [
            'id' => $data->id,
            'documentTypeId' => $data->document_type_id,
            'name' => $data->name,
            'number' => $data->number,
            'issuerId' => $data->issuer_id,
            'issueDate' => $data->issue_date,
            'issuePlace' => $data->issue_place,
            'adReferendum' => $data->ad_referendum,
            'subject' => $data->subject,
            'destinatary' => $data->destinatary,
            //'anexosSectionTypeId' => $data->anexos_section_type_id,
            'body' => json_decode($data->body),
            'anexos' => Anexo::with(['file'])->where('document_id', $data->id)->get()
        ];
    }

    public function exportAnexo(Request $request, $id){
        $document = Document::where('id', $id)->first();
        $html = "";
        if($document) {
            //$html = view($document->documentType->view)->with(['document' => $document, 'isCopy' => true, 'anexos' => Anexo::with(['file'])->where('document_id', $id)->get()]);
            $html = view($document->documentType->view)->with([
                'document' => $document, 
                'isCopy' => true, 
                'anexos' => Anexo::with(['file'])->where('document_id', $document->id)->get(),
                'fileId' => Heading::where([
                    ['issuer_id', '=', $document->issuer_id],
                    ['year', '=', date('Y', strtotime($document->issue_date))]
                ])->first()->file->id
            ]);
        } 
        return $html;
        /*$snappdf = new \Beganovich\Snappdf\Snappdf();
        $pdf = $snappdf
            ->setHtml($html->render())
            ->waitBeforePrinting(10000) 
            ->generate();
        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'.pdf"; filename*="'.$filename.'.pdf"')
            ->header('Access-Control-Expose-Headers', 'Content-Disposition');

       */
    }

   
}
