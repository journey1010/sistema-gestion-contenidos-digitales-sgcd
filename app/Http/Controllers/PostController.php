<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\Create;
use App\Http\Requests\Post\Paginate;
use App\Http\Requests\Post\Single;
use App\Http\Requests\Post\Delete;
use Illuminate\Http\UploadedFile;

use App\Models\Post;
use Exception;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api',['except' => ['paginatePosts', 'getSpecificPost']]);       
    }

    public function createPost(Create $request)
    {
        try {
            list($uniqueName, $path) = $this->saveFile($request->userId, $request->file('file'));
            Post::savePost($request->appName,$request->title, $request->description,$uniqueName, $path, $request->userId);
            return response()->json([
                'status' => 'success',
                 'message'=> 'Publicación registrada'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                 'message'=> $e->getMessage()
            ], 400);
        }
    }

    public function paginatePosts(Paginate $request)
    {
        try {
            $paginatePosts = Post::getPaginatePost($request->appName, $request->numberItems, $request->page);
            return response()->json([
                'status' => 'success',
                'data' => $paginatePosts['items'],
                'total_items' => $paginatePosts['total_items']
            ], 200);
        } catch(Exception $e){
            return response()->json([
                'status' => 'error',
                 'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getSpecificPost(Single $request)
    {
        try {
            $post = Post::getSpecificPost($request->postId);
            return response()->json([
                'status' => 'success',
                'data' => $post
            ]);
        } catch(Exception $e){
            return response()->json([
                'status' => 'succes',
                'message' => 'Publicación no encontrada'
            ],  500);   
        }
    }

    public function invalidatePost(Delete $request)
    {
        try {
            $post = Post::find($request->postId);
            if(!$post){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Publicación no encontrada'
                ], 404);
            }
            $post->delete();
            return response()->json([
                'status'=> 'success', 
                'message'=>'Registro Eliminado'
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error', 
                'message'=> 'Estamos experimentando problemas :('
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