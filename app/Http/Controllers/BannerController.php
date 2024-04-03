<?php
/*
namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class BannerController extends Controller
{
    public function __construct( )
    {
        $this->middleware('auth:api', ['except' => ['getBanners']]);
    }

    public function createBanner(Request $request)
    {
        try {
            $request->validate([
                'files.*' => [
                  'required',
                  'image',
                  'mimetypes:image/*', 
                  'dimensions: min-width=1000|min-height:500', 
                ],
              ]);              
        } catch(ValidationException $e){
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }

        try{
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $unique_name = date('YmdHis') . rand(10,99);
        
                    $path = $file->storeAs(

                        'banners/' . date('Y/m'),
                        $unique_name . '.' . $extension,
                        'public'
                    );
                    BannerModel::create([
                        'original_name' => $filename,
                        'unique_name' => $unique_name,
                        'type_file' => $extension,
                        'path_file' => $path,
                        'status'  => 1,
                        'date_create' => date('Y-m-d H:i:s')
                    ]);
                }
                return response()->json(['status' => 'success', 'message' => 'Banner registered successfuly'], 201);
            }
            return response()->json(['status' => 'error', 'message' => 'Files are required'], 500);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }


    public function getBanners (Request $request) 
    {
        $request->validate(['numberItems' => 'required|numeric' ]);
        try {
            $images = BannerModel::getListBanner($request->numberItems);
            return response()->json(['status'=> 'success', 'message' => 'List get successfully', 'data' => $images], 200);
        }  catch(\Exception $e){
            return response()->json(['status'=>'error', 'messsage' => 'No mis imagenes'], 500);
        }
    }

    public function deleteBanner(Request $request)
    {
        try{
            $request->validate(['idBanner' => 'required|numeric']);
            $file = BannerModel::deleteBanner($request->idBanner);
            Storage::disk('public')->delete($file);
            return response()->json(['status'=>'success', 'message' => 'Banner eliminado.'], 200);
        }catch(\Exception $e){
            return response()->json(['status'=>'error', 'messsage' => $e->getMessage()], 500);
        }
    }
} 

*/
