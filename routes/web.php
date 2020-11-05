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

// front routes
Auth::routes();
Route::get('/', function () {
  return redirect('/adminpanel');
});

//admin routes
Route::resource('adminpanel/', 'adminloginController');
Route::group(['middleware' => ['adminauth:admin']], function () {
  //user routs
  Route::resource('adminpanel/users', 'adminmemberController');
  Route::delete('myusersDeleteAll', 'adminmemberController@deleteAll');

  //setting routes
  Route::resource('adminpanel/conditions', 'adminconditionsController');
  Route::resource('adminpanel/setapp', 'adminchangelogoController');

 //services routes
  Route::resource('adminpanel/services', 'adminserviceController');
  Route::delete('myservicesDeleteAll', 'adminserviceController@deleteAll');

  Route::resource('adminpanel/bills', 'adminillController');
  Route::resource('adminpanel/orders', 'adminorderController');
  Route::delete('myordersDeleteAll', 'adminorderController@deleteAll');
  Route::resource('adminpanel/transfers', 'admintransferController');
  Route::delete('mytransferDeleteAll', 'admintransferController@deleteAll');

  //categories
  Route::resource('adminpanel/categories', 'adminCategoryController');
  Route::resource('adminpanel/wcategories', 'adminweightsController');
  Route::delete('mycategoroiesDeleteAll', 'adminCategoryController@deleteAll');

  //city
  Route::resource('adminpanel/cities', 'adminCityController');
  Route::resource('adminpanel/districts', 'adminDistrictController');
  Route::get('adminpanel/list_cities', 'adminCityController@list_cities')->name('admin.list_cities');

  //Booking
  Route::resource('adminpanel/booking', 'adminbookingController');
  Route::delete('mybookingDeleteAll', 'adminbookingController@deleteAll');

  //rates
  Route::get('adminpanel/catrates/{id}', 'adminCategoryController@showrates');
  Route::delete('adminpanel/ratedel/{id}', 'adminCategoryController@delerates');

  //contact us
  Route::get('adminpanel/contact-us', 'admincontactController@getContact');
  Route::get('adminpanel/contact-us/{id}', 'admincontactController@showContact');
  Route::post('adminpanel/contact-us', 'admincontactController@saveContact');
  Route::delete('adminpanel/message/{id}', 'admincontactController@destroy');

  //comments
  Route::get('adminpanel/showcomments/{id}', 'adminCategoryController@showcomments');


});

//admin logout
Route::Delete('adminpanel/{id}', 'adminloginController@destroy');
