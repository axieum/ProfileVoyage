<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
})->name('index');

Auth::routes();
Route::post('/register/check/{type}', 'Auth\RegisterController@check')->name('auth.check');
Route::get('/register/verify/{token}', 'Auth\RegisterController@verify')->name('auth.verify');

Route::prefix('/account')->group(function() {
    Route::get('/', 'AccountController@edit')->name('account.edit');
    Route::put('/', 'AccountController@update')->name('account.update');

    Route::get('/email', 'AccountController@editEmail')->name('account.edit.email');
    Route::put('/email', 'AccountController@updateEmail')->name('account.update.email');
    
    Route::get('/security', 'AccountController@editSecurity')->name('account.edit.security');
    Route::put('/security', 'AccountController@updateSecurity')->name('account.update.security');

    Route::delete('/delete', 'AccountController@destroy')->name('account.delete');
});
