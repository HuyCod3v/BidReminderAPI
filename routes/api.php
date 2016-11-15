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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


Route::resource('users', 'UserAPIController');

Route::resource('biddings', 'BiddingAPIController');

Route::resource('repositories', 'RepositoryAPIController');

Route::resource('products', 'ProductAPIController');

Route::resource('carts', 'CartAPIController');

Route::get('authenticate/sign-in', 'AuthenticateAPIController@getSignIn');

Route::get('authenticate/sign-up', 'AuthenticateAPIController@getSignUp');

Route::get('authenticate/re-sign-in', 'AuthenticateAPIController@getReSignIn');

