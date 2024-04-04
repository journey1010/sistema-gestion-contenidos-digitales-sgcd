<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\PostController;

Route::post('/auth/login', [AuthController::class, 'login']);
Route::patch('/auth/change/password', [AuthController::class, 'changePass']);
Route::post('/user/register', [AuthController::class, 'register']);
Route::post('/auth/refresh', [AuthController::class,  'refreshToken']);
Route::post('/doc/new', [DocumentController::class, 'save']);
Route::post('/doc/type-doc/new', [DocumentController::class, 'saveTypeDoc']);
Route::get('/doc/type-doc/list', [DocumentController::class,'listTypeDoc']);
Route::get('/doc/list/all', [DocumentController::class, 'listDocAll']);
Route::get('/doc/list/type', [DocumentController::class, 'listDocPerType']);
Route::post('/banner/new', [BannerController::class, 'createBanner']);
Route::get('/banner/list', [BannerController::class, 'getBanners']);
Route::delete('/banner/single', [BannerController::class, 'delete']);
Route::post('/post/new', [PostController::class, 'createPost']);
Route::get('/post/list', [PostController::class, 'paginatePosts']);
Route::get('/post/single', [PostController::class, 'getSpecificPost']);
Route::delete('/post/quit', [PostController::class, 'invalidatePost']);