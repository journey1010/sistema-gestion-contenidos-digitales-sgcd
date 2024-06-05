<?php

namespace App\Http\Controllers;

use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ListUser;
use App\Http\Requests\ChangePass;

use App\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth', ['except'=> ['login', 'refreshToken']]);
    }

    public function login(LoginRequest $request)
    {
      $credentials = $request->only('email', 'password');
      if (!$token = Auth::guard('api')->attempt($credentials)) {
          return response()->json([
              'status'=>'error', 
              'message' => 'Unauthorized'
          ], 401);
      }
  
      $user = Auth::guard('api')->user();
      $token = JWTAuth::claims([
          'type' => $user->rol,
          'email' => $user->email
        ])->fromUser($user);
      
      return response()->json([
          'status' => 'success',
          'token' => $token,
          'data' => $user 
      ], 200);
    }

    public function refreshToken()
    {
      try {
          $oldToken = JWTAuth::parseToken();
        
          $email = $oldToken->getPayload()->get('email');
          $user = User::where('email', $email)->first();
          if (!$user && $user->status == 0) {
            return response()->json([
              'status' => 'error',
              'message' => "Esta cuenta de usuario, esta Inactiva",
            ], 401);
          }
        
          $newToken = JWTAuth::fromUser($user, [], true);
        
          return response()->json([
            'status' => 'success',
            'token' => $newToken,
            'data' => $user,
          ], 200);
          
        } catch (\Exception $e) {
          return response()->json([
            'status' => 'error',
            'message' => 'Estamos experimentando dificultades'
          ], 401);
        }
    }

    public function register(RegisterRequest $request)
    {
      try {
        User::saveUser(
          $request->appName,
          $request->name,
          $request->email,
          $request->password,
          $request->rol,
          
        );
        return response()->json([
          'status' => 'success',
          'message' => 'Nuevo cuenta de usuario agregada',
        ], 200);       
      } catch(\Exception $e){
        return response()->json([
          'status' => 'error',
          'message' => 'Ocurrio un error inesperado al guardar!'
        ], 500);
      }
    }

    public function changePass(ChangePass $request)
    {
      try{
        if(!User::changePassword($request->password, $request->userId)){
          return response()->json([
            'status' => 'error',
            'message' => 'Usuario no encontrado'
          ], 404);
        }
        return response()->json([
          'status' => 'success',
          'message' => 'Contraseña guardada'
        ], 200);
      }catch(\Exception $e){
        return response()->json([
          'status' => 'error',
          'message' => 'Estamos experimentando problemas',
        ], 500);
      }
    }

    public function listUser(ListUser $request)
    {
      try{
        $list = User::select('id', 'name', 'email', 'status', 'created_at as date')
          ->where('app_name', $request->appName)
          ->orderBy('created_at', 'desc')
          ->paginate($request->numberItems, ['*'], 'page', $request->page);
        return response()->json([
          'status' => 'succes',
          'items' => $list->items(),
          'total_items' => $list->total(),
        ], 200);
      }catch(\Exception $e){
        return response()->json([
          'status' => 'error',
          'message' => 'Estamos experimentando problemas'
        ]);
      }
    }
}