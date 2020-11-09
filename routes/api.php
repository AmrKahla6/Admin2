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
Route::post('contactus', 'API\appsettingController@contactus');
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


//App Setting routes
Route::post('settinginfo', 'API\appsettingController@settingindex');
Route::post('home', 'API\appsettingController@home');
// Route::post('addbooking', 'API\appsettingController@addbooking');



//category routes
Route::post('allcities', 'API\catController@allcities');
Route::post('alldistricts', 'API\catController@alldistricts');
Route::post('citydistricts', 'API\catController@citydistricts');
Route::post('showcat', 'API\catController@showcat');


//servies routes
Route::post('allcat', 'API\serviceController@allcat');
Route::post('allicons', 'API\serviceController@allicons');
Route::post('showservice', 'API\serviceController@showservice');

//comments routes
Route::post('makecomment', 'API\commentsController@makecomment');
Route::post('mycomment', 'API\commentsController@mycomment');
Route::post('showcomments', 'API\commentsController@showcomments');
Route::post('deletecomment', 'API\commentsController@deletecomment');

//rates routes
Route::post('makerate', 'API\ratesController@makerate');
Route::post('myrate', 'API\ratesController@myrate');
Route::post('showrates', 'API\ratesController@showrates');
Route::post('deleterate', 'API\ratesController@deleterate');


//Booking routes
Route::post('makebooking', 'API\bookingController@makebooking');
Route::post('mybooking', 'API\bookingController@mybooking');
Route::post('showbooking', 'API\bookingController@showbooking');
Route::post('delbooking', 'API\bookingController@delbooking');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
