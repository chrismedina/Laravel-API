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

Route::post('login', 'PassportController@login');

Route::post('register', 'UserController@store');

Route::middleware('auth:api', 'throttle:30,1')->group(function() {

    Route::apiResource('users', 'UserController')->middleware('auth:api');

});