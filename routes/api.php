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
Route::group(['middleware' => ['throttle:5,1']], function () {
    Route::get('films', 'App\Http\Controllers\FilmController@index');
    Route::get('films/{id}', 'App\Http\Controllers\FilmController@show');

    Route::get('films/{id}/critics', 'App\Http\Controllers\CriticController@show');
    Route::get('films/{id}/actors', 'App\Http\Controllers\ActorController@show');


    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('critics', 'App\Http\Controllers\CriticController@store');
        Route::post('user', 'App\Http\Controllers\UserController@show');
        Route::put('user', 'App\Http\Controllers\UserController@update');
        
        Route::group(['middleware' => ['admin']], function () {
            Route::post('films', 'App\Http\Controllers\FilmController@store');
            Route::put('films/{id}', 'App\Http\Controllers\FilmController@update');
            Route::delete('films/{id}', 'App\Http\Controllers\FilmController@destroy');
        });
    });

    Route::post('users/create', 'App\Http\Controllers\UserController@store');
});