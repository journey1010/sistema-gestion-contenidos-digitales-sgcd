<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\Banner\CreateBanner;
use App\Http\Requests\Banner\GetBanners;
use App\Http\Requests\Banner\Delete;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

use App\Models\Banners;

class BannerController extends Controller
{
    public function __construct( )
    {
        $this->middleware('auth:api', ['except' => ['getBanners']]);
    }

    public function createBanner(CreateBanner $request): JsonResponse
    {
        try {
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
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
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error', 
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getBanners (GetBanners $request): JsonResponse
    {
        try {
            $images = Banners::getListBanner($request->numberItems);
            return response()->json([
                'status'=> 'success',
                'data' => $images
            ], 200);
        } catch(ValidationException $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }  catch(\Exception $e){
            return response()->json([
                'status'=>'error', 
                'messsage' => 'Sin lista'
            ], 500);
        }
    }

    public function delete(Delete $request): JsonResponse
    {
        try{
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
