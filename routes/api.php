<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
/**
 * Article routes
 */
Route::get('articles', 'ArticleController@index');
Route::get('article/{id}', 'ArticleController@show');
Route::post('article', 'ArticleController@store');
Route::put('article', 'ArticleController@store');
Route::delete('article/{id}', 'ArticleController@destroy');

/**
 * Category routes
 */
Route::get('categories', 'CategoryController@index');
Route::get('category/{id}', 'CategoryController@show');
Route::post('category', 'CategoryController@store');
