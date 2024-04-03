<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api',['except' => ['getLastPost', 'getPaginatePosts', 'getSpecificPost']]);       
    }

    public function createPost(Request $request)
    {
        $request->validate([
            '' => '',
            'files' => [
              'required',
              'image',
              'mimetypes:image/*', 
              'dimensions: min-width=1000|min-height:500', 
            ],
          ]);   

        try {
            $Post = PostModel::savePost($data);

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $unique_name = date('YmdHis') . rand(10,99);
        
                    $path = $file->storeAs(

                        'Posts/' . date('Y/m'),
                        $unique_name . '.' . $extension,
                        'public'
                    );
        
                    PostFile::create([
                        'id_Post_file' => $Post->id,
                        'original_name' => $filename,
                        'unique_name' => $unique_name,
                        'type_file' => $extension,
                        'path_file' => $path,
                        'date_create' => date('Y-m-d')
                    ]);
                }
            }
            return response()->json(['status' => 'success', 'message'=> 'Post created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message'=> $e->getMessage()], 400);
        }
    }

    public function getLastPosts(Request $request)
    {
        try {
            $request->validate(['category' => 'nullable|string|min:4', 'numberItems' => 'required|string']);
            if (!isset($data['category'])) {
                $data['category'] = null; 
            }
        } catch (ValidationException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }

        try {
            $listPosts = PostModel::getLastPosts($request->category, $request->numberItems);
            return response()->json(
                ['status' => 'success', 
                'message'=> 'congratulations, you have last news', 
                'data' => $listPosts
                ], 200
            );
        } catch (\Exception $e){
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function getPaginatePosts(Request $request)
    {

            $data = $request->only('page', 'category', 'numberItems');
            $validator = Validator::make($data, [
                'page' => 'required|numeric',
                'category' => 'required|string|min:3',
                'numberItems' => 'required|numeric'
            ]);

            if($validator->fails()){
                return  response()->json(['status' => 'error', 'message' => $validator->messages()], 400);
            }

        try {
            $paginatePosts = PostModel::getPaginatePosts($request->category, $request->numberItems, $request->page);
            return response()->json([
                'status' => 'success',
                'message' => 'Toma tu taper tilin',
                'data' => $paginatePosts['data'],
                'current_page' => $paginatePosts['current_page'],
                'total_pages' => $paginatePosts['total_pages'],
                'per_pages' => $paginatePosts['per_pages'],
                'last_page' => $paginatePosts['last_page']
            ], 200);
        } catch(\Exception $e){
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function getSpecificPost(Request $request)
    {
        $request->validate(['idPost' => 'nullable|numeric']);
        try {
            $Post = PostModel::getSpecificPost($request->idPost);
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $Post
            ]);
        } catch(\Exception $e){
            return response()->json(['status' => 'succes', 'message' => $e->getMessage()],  500);   
        }
    }

    public function invalidatePost(Request $request)
    {
        $data = $request->all();
        $validate = Validator::make($data, ['idPost'=> 'required|numeric']);
        if($validate->fails()){
            return response()->json(['status' => 'error', 'message' => $validate->messages()], 400);
        }

        try {
            $Post = PostModel::find($request->idPost);
            $Post->status = false;
            $Post->update();
            return response()->json(['status'=> 'success', 'message'=>'Update successfully'], 200);
        }catch(\Exception $e){
            return response()->json(['status' => 'error', 'message'=>'User not found'], 400);
        }
    }

    public  function updatePost(Request $request) 
    {
        $data = $request->all();
        try {
            $validator = Validator::make($data,  [
                'idPost' => 'required|numeric',
                'title' => 'required|string|max:400',
                'body' => 'required|string',
                'activity_date' => 'nullable|date',
                'category_Post' => 'required|string|max:30',
                'status' => 'nullable|string|max:30',
                'author' => 'nullable|string|max: 255',           
            ]);

            if($validator->fails()){
                return response()->json(['status' => 'error', 'message' => $validator->messages()], 500);
            }

            if (!isset($data['author'])) {
                $data['author'] = 'Somos Perú Loreto'; 
            }
            
            if(!isset($data['activity_date'])){
                $data['activity_date'] = null;   
            }

            PostModel::updatePost($data);
            return response()->json(['status' => 'status', 'message' => 'Update successfully'], 201);
        }catch(\Exception $e){
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
