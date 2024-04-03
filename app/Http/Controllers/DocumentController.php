<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use App\Http\Requests\Doc\SaveDocRequest;
use App\Http\Requests\Doc\TypeDoc;
use App\Http\Requests\Paginate;
use App\Models\DocumentsModel;
use App\Models\TypeDoc as TypeDocModel;

class DocumentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except'=> ['listDocPerUser', 'listTypeDoc']]);
    }

    public function save(SaveDocRequest $request): JsonResponse
    {
        try{

            if(!$request->has('file')){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Archivo no encontrado', 
                ], 404);
            }

            list($originalName,$pathFile) = $this->saveFile($request->userId, $request->file('file'));
            
            DocumentsModel::saveDoc(
                $request->typeDoc,
                $request->userId,
                $request->title,
                $request->description,
                $originalName,
                $pathFile
            );
            
            return response()->json([
                'status' => 'success',
                'message' => 'Registro terminado'
            ], 200);
        }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Registro no guardado'
            ], 500);
        }
    }

    public function listDocAll(Paginate $request): JsonResponse
    {
        try{

            return response()->json([
                'status' => 'success',
            ], 200);
        }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Estamos experimentando problemas'
            ], 500);
        }
    }

    public function saveTypeDoc(TypeDoc $request): JsonResponse
    {
        try {
            TypeDocModel::storeDoc($request->name, $request->description);
            return response()->json([
                'status' => 'success',
                'message' => 'Registro guardado'
            ], 200);
        }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Estamos experimentando problemas'
            ], 500);
        }
    }

    public function listTypeDoc(): JsonResponse
    {
        try {
            $list  = TypeDocModel::select('id', 'name')
                ->get();
            return response()->json([
                'status' => 'success',
                'data' =>  $list
            ], 200);
        }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Estamos experimentando problemas'
            ], 500);
        }
    }

    private function saveFile(int $id , UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();
        $uniqueName = time() . '_' . $id . '.' . $extension;
        $path = $file->storeAs(
            'docs/' . date('Y/m'),
            $uniqueName,
            'public'
        );
    
        return [$uniqueName, $path];
    }
}