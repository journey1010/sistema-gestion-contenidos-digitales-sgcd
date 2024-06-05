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
        $this->middleware('auth', ['except'=> ['listDocPerUser', 'listTypeDoc', 'listDocAll', 'listDocPerType']]);
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
                $request->appName,
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
            $list = DocumentsModel::listAllDoc($request->itemsPerPage, $request->page);
            return response()->json([
                'status' => 'success',
                'data' => $list['items'],
                'total_items' => $list['total_items']
            ], 200);
        }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Estamos experimentando problemas'
            ], 500);
        }
    }

    public function listDocPerType(Paginate $request): JsonResponse
    {
        try{
            if(!$request->has('typeDocId')){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Debe proporcionar el ID del tipo de documento',
                ], 422);
            }
            $list = DocumentsModel::listDocPerType($request->appName, $request->itemsPerPage,$request->page, $request->typeDocId);
            return response()->json([
                'status' => 'success',
                'data' => $list['items'],
                'total_items' => $list['total_items']
            ], 200);
        }catch(Exception $e ){
            return response()->json([
                'status' => 'error',
                'message' => 'Registro no guardado'
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