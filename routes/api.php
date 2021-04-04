<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::get('films','App\Http\Controllers\FilmController@index');
Route::get('films/{id}','App\Http\Controllers\FilmController@show');

Route::get('films/{id}/critics','App\Http\Controllers\CriticController@show');
Route::get('films/{id}/actors','App\Http\Controllers\ActorController@show');
//Route::get('films/{id}/critics','App\Http\Controllers\FilmController@showWithCritics');
//Route::get('films/{id}/actors','App\Http\Controllers\FilmController@showWithActors');

Route::post('users','App\Http\Controllers\UserController@store');