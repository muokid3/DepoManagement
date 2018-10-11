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


Auth::routes();
Route::get('/logout', 'Auth\LoginController@logout');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/','HomeController@index')->middleware('perm:1');


    Route::get('/products','ProductController@index')->middleware('perm:1');
    Route::get('/products/{id}','ProductController@product')->middleware('perm:1');
    Route::post('/products/new','ProductController@new_product')->middleware('perm:1');
    Route::post('/products/update','ProductController@update_product')->middleware('perm:1');


    Route::get('/companies','CompanyController@index')->middleware('perm:1');
    Route::get('/companies/{id}','CompanyController@company')->middleware('perm:1');
    Route::post('/companies/new','CompanyController@new_company')->middleware('perm:1');
    Route::post('/companies/update','CompanyController@update_company')->middleware('perm:1');




    Route::get('/depots','DepotController@index')->middleware('perm:1');
    Route::get('/depots/{id}','DepotController@depot')->middleware('perm:1');
    Route::post('/depots/new','DepotController@new_depot')->middleware('perm:1');
    Route::post('/depots/update','DepotController@update_depot')->middleware('perm:1');



    Route::get('/drivers','DriverController@index')->middleware('perm:1');
    Route::get('/drivers/{id}','DriverController@driver')->middleware('perm:1');
    Route::post('/drivers/new','DriverController@new_driver')->middleware('perm:1');
    Route::post('/drivers/update','DriverController@update_driver')->middleware('perm:1');



    Route::get('/vehicles','VehicleController@index')->middleware('perm:1');
    Route::get('/vehicles/{id}','VehicleController@vehicle')->middleware('perm:1');
    Route::post('/vehicles/new','VehicleController@new_vehicle')->middleware('perm:1');
    Route::post('/vehicles/update','VehicleController@update_vehicle')->middleware('perm:1');



    Route::get('/users','UserController@index')->middleware('perm:1');

});





