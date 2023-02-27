<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\RedactaUser;
use App\Models\Issuer;
use App\Models\DocumentType;
use Illuminate\Support\Facades\Auth;



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
        $document = new Document();
        $document->set($this->formatDocumentDataForStoring($request));
        $document->save();
        return response()->json([
            'status' => 200,
            'description' => 'OK',
            'data' => $this->formatDocumentDataForRetrieving($document)        
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $document = Document::where('id', $id)->first();
        //->where('user_id', $request->user()->id) Implementa restricción de que solo el creador del documento puede accederlo
        if($document){
            return response()->json([
                'status' => 200,
                'description' => 'OK',
                'data' => $this->formatDocumentDataForRetrieving($document)            
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'description' => 'El documento que desea modificar no existe'        
            ], 404);
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
        $document = Document::where('id', $id)->first();
        //->where('user_id', $request->user()->id) Implementa restricción de que solo el creador del documento puede editarlo
        if($document){
            $document->set($this->formatDocumentDataForStoring($request));
            $document->save();
            return response()->json([
                'status' => 200,
                'description' => 'OK',
                'data' => $this->formatDocumentDataForRetrieving($document)           
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'description' => 'El documento que desea modificar no existe'        
            ], 404);
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

    public function exportToPDF($id){
        
    }

    private function formatDocumentDataForStoring($data){  
        return [
            'document_type_id' => $data->documentTypeId,
            'name' => $data->name,
            'issuer_id' => $data->issuerId,
            'issue_date' => $data->issueDate,
            'issue_place' => $data->issuePlace,
            'ad_referendum' => $data->adReferendum,
            'subject' => $data->subject,
            'destinatary' => $data->destinatary,
            'body' => json_encode($data->body)
        ];
    }
    
    private function formatDocumentDataForRetrieving($data){  
        return [
            'id' => $data->id,
            'documentTypeId' => $data->document_type_id,
            'name' => $data->name,
            'issuerId' => $data->issuer_id,
            'issueDate' => $data->issue_date,
            'issuePlace' => $data->issue_place,
            'adReferendum' => $data->ad_referendum,
            'subject' => $data->subject,
            'destinatary' => $data->destinatary,
            'body' => json_decode($data->body)
        ];
    }



   
}
