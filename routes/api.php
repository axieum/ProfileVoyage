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

Route::post('/register/checkemail', 'Auth\RegisterController@checkEmail')->name('auth.check');
Route::post('/profiles/checklink', 'ProfileController@checkLink')->name('profile.check.link');
Route::post('/profiles/checkname', 'ProfileController@checkName')->name('profile.check.name');
