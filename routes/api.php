<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', 'App\Http\Controllers\UserController@register');
Route::post('/login', 'App\Http\Controllers\UserController@login');
Route::get('/logout', 'App\Http\Controllers\UserController@logout');
Route::post('/update', 'App\Http\Controllers\UserController@update');

Route::post('/store','App\Http\Controllers\ProductController@store');
Route::get('/getproduct','App\Http\Controllers\ProductController@getproduct');

Route::get('/search','App\Http\Controllers\SearchController@search');
