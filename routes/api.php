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
Route::POST('register', 'API\userController@register');
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
// Route::post('resend_activation_code', 'API\userController@resend_activation_code');


//App Setting Controller
Route::post('settinginfo', 'API\appsettingController@settingindex');
Route::post('home', 'API\appsettingController@home');
// Route::post('addbooking', 'API\appsettingController@addbooking');
Route::post('districts', 'API\appsettingController@districts');


//category Controller
Route::post('allcities', 'API\catController@allcities');
Route::post('showcat', 'API\catController@showcat');


//servies Controller
Route::post('allcat', 'API\serviceController@allcat');
Route::post('allicons', 'API\serviceController@allicons');
Route::post('showservice', 'API\serviceController@showservice');


//Booking Controller
Route::post('makebooking', 'API\bookingController@makebooking');
Route::post('mybooking', 'API\bookingController@mybooking');
Route::post('showbooking', 'API\bookingController@showbooking');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
