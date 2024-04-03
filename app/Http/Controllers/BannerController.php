<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

use App\Models\Banners;

class BannerController extends Controller
{
    public function __construct( )
    {
        $this->middleware('auth:api', ['except' => ['getBanners']]);
    }

    public function createBanner(Request $request): JsonResponse
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
                    Banners::saveBanner(
                        $extension,
                        $path,
                    );
                }
                return response()->json([
                    'status' => 'success',
                     'message' => 'Banner Guardado'
                ], 201);
            }
            return response()->json([
                'status' => 'error',
                 'message' => 'Files are required'
            ], 500);
        } catch(ValidationException $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error', 
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getBanners (Request $request): JsonResponse
    {
        $request->validate(['numberItems' => 'required|numeric' ]);
        try {
            $images = Banners::getListBanner($request->numberItems);
            return response()->json([
                'status'=> 'success',
                'data' => $images
            ], 200);
        }  catch(\Exception $e){
            return response()->json([
                'status'=>'error', 
                'messsage' => 'Sin lista'
            ], 500);
        }
    }

    public function delete(Request $request): JsonResponse
    {
        try{
            $request->validate(['bannerId' => 'required|numeric']);
            $file = Banners::deleteBanner($request->bannerId);
            Storage::disk('public')->delete($file);
            return response()->json(['status'=>'success', 'message' => 'Banner eliminado.'], 200);
        } catch(ValidationException $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }catch(\Exception $e){
            return response()->json([
                'status'=>'error',
                 'messsage' => $e->getMessage()
            ], 500);
        }
    }
} 
