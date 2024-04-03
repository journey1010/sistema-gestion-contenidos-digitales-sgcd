<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/user/register', [AuthController::class, 'register']);
Route::post('/auth/refresh', [AuthController::class,  'refreshToken']);
Route::post('/doc/new', [DocumentController::class, 'save']);
Route::post('/doc/type-doc/new', [DocumentController::class, 'saveTypeDoc']);
Route::get('/doc/type-doc/list', [DocumentController::class,'listTypeDoc']);
