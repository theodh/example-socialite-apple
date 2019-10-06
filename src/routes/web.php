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
    return view('welcome');
});

Route::get('social-auth/provider/{provider}', 'Auth\SocialAuthController@getProvider')->where('provider', '[A-Za-z]{1,32}');
Route::get('social-auth/handle/{provider}', 'Auth\SocialAuthController@getHandleCallback')->where('provider', '[A-Za-z]{1,32}');
Route::post('social-auth/handle/{provider}', 'Auth\SocialAuthController@getHandleCallback')->where('provider', '[A-Za-z]{1,32}');
