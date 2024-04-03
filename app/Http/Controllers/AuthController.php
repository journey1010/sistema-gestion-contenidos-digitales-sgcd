<?php

namespace App\Http\Controllers;

use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

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
          'userId' => $user->id,
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
}