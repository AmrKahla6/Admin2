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

//user Controller routes
Route::post('register', 'API\userController@register');
Route::post('login', 'API\userController@login');
Route::post('profile', 'API\userController@profile');
Route::post('rechangepass', 'API\userController@rechangepass');
Route::post('updateprofile', 'API\userController@update');
Route::post('forgetpassword', 'API\userController@forgetpassword');
Route::post('activcode', 'API\userController@activcode');
Route::post('mynotification', 'API\userController@mynotification');
Route::post('updatefirebasebyid', 'API\userController@updatefirebasebyid');
Route::post('deletenotification', 'API\userController@deletenotification');
Route::post('account_activation', 'API\userController@account_activation');
Route::post('resend_activation_code', 'API\userController@resend_activation_code');


//App Setting Controller
Route::post('settinginfo', 'API\appsettingController@settingindex');
Route::post('home', 'API\appsettingController@home');
Route::post('addtransfer', 'API\appsettingController@addtransfer');
Route::post('districts', 'API\appsettingController@districts');


//Item Controller
Route::post('showitem', 'API\itemController@showitem');
Route::post('allcities', 'API\itemController@allcities');


//Order Controller
Route::post('makeorder', 'API\orderController@makeorder');
Route::post('myorders', 'API\orderController@myorders');
Route::post('showorder', 'API\orderController@showorder');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
