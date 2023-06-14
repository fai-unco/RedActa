<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\RedactaUser;
use Illuminate\Support\Facades\Auth;




class FileController extends Controller
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
            $file = $request->file('file');
            $name = $file->getClientOriginalName();
            //$file = $request->file('file')->storeAs('uploads', $name);
            $uploadsDirPath = env('STATIC_FILES_DIRECTORY').'/uploads';
            $file->move($uploadsDirPath, $name);
            $file = new File();
            $file->filename = $name;
            $file->redactaUser()->associate(RedactaUser::find($request->user()->id));
            $file->save();
            $currentPath = getcwd();
            chdir($uploadsDirPath);
            //$fileExtension = pathinfo($name, PATHINFO_EXTENSION);
            //if($fileExtension == 'pdf'){
            shell_exec('pdftoppm -png -r 300 "'.$name.'" '.$file->id.'; rm "'.$name.'"');
            //}
            /*else if($fileExtension == 'jpg' || $fileExtension == 'png'){
                shell_exec('mv '.$name.' '.$file->id.'-1.'.$fileExtension);
            }*/
            chdir($currentPath);
            return response()->json([
                'status' => 200,
                'message' => 'OK',
                'data' => [
                    'filename' => $name,
                    'id' => $file->id
                ]       
            ]);     
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $file = File::find($id);
            if(!$file){ // || $request->user()->id != $file->user->id
                return response()->json([
                    'status' => 404,
                    'message' => 'Archivo inexistente'        
                ], 404); 
            }
            $file->delete();
            return response()->json([
                'status' => 200,
                'message' => 'OK',
                'data' => $id          
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Error en el servidor. Reintente la operación'
            ], 500);
        }   
    }

    public function validateRequest($request) {
        $validator = Validator::make($request->all(), [
            'file' => 'mimes:pdf',
        ])->validate();
    }
}
