<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\RedactaUser;
use App\Models\Issuer;
use App\Models\Anexo;
use App\Models\DocumentType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;




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
        $this->validateRequest($request);
        try {
            $document = new Document();
            $document->set($this->formatDocumentDataForStoring($request));
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
        $this->validateRequest($request);
        try {
            $loggedInUserId = $request->user()->id;        
            $document = Document::where('id', $id)->first();
            if(!$document || $document->redactaUser->id != $loggedInUserId){
                return response()->json([
                    'status' => 404,
                    'message' => 'El documento que desea modificar no existe'        
                ], 404);  
            }  
            $document->set($this->formatDocumentDataForStoring($request));
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
        $views = [
            1 => 'res-dec-disp', //if document type == resolucion
            2 => 'res-dec-disp', //if document type == declaracion
            3 => 'res-dec-disp', //if document type == disposicion
            4 => 'acta', //if document type == acta
            5 => 'memo', //if document type == memo
            6 => 'nota', //if document type == nota
        ];        
        $html = view($views[$document->documentType->id])->with([
            'document' => $document, 
            'isCopy' => $isCopy, 
            'anexos' => Anexo::with(['file'])->where('document_id', $document->id)->get()
        ]);
        $snappdf = new \Beganovich\Snappdf\Snappdf();
        $pdf = $snappdf
            ->setHtml($html->render())
            ->waitBeforePrinting(10000) 
            ->generate();
        return $pdf;
    }

    private function validateRequest($request) {
        $validator = Validator::make($request->all(), [
                'documentTypeId' => 'required|numeric',
                'name' => 'required',
                'number' => 'required|numeric',
                'issuerId' => 'required|numeric',
                'issueDate' => 'required|date',
                'issuePlace' => 'required',
                'adReferendum' => 'boolean',
                //'anexosSectionTypeId' => 'required'
            ], [
                'required' => 'El campo :attribute es requerido',
                'numeric' => 'El campo :attribute debe ser un número',
                'date' => 'El campo :attribute debe ser una fecha en formato dd/mm/yyyy'
            ], [
                'documentTypeId' => '"tipo de documento"',
                'name' => '"nombre de documento"',
                'number' => '"número"',
                'issuerId' => '"dependencia emisora"',
                'issueDate' => '"fecha de emisión"',
                'issuePlace' => '"lugar de emisión"',
                'adReferendum' => '"ad referendum"',
                //'anexosSectionTypeId' => '"tipo de anexo"'
            ])->stopOnFirstFailure(true);
        $validator->validate();
    }

    private function formatDocumentDataForStoring($data){  
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
    }
    
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
        $views = [
            1 => 'res-dec-disp', //if document type == resolucion
            2 => 'res-dec-disp', //if document type == declaracion
            3 => 'res-dec-disp', //if document type == disposicion
            4 => 'nota', //if document type == nota
            5 => 'acta', //if document type == acta
            6 => 'memo', //if document type == memo
        ];
        $html = "";
        if($document) {
            $filename = $document->name;
            $html = view($views[$document->documentType->id])->with(['document' => $document, 'isCopy' => true, 'anexos' => Anexo::with(['file'])->where('document_id', $id)->get()]);
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
