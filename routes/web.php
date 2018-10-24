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
    Route::get('/','HomeController@index');

    Route::get('/get_orgs/{user_group}', 'HomeController@get_orgs');



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
    Route::get('/vehicles/{id}','VehicleController@vehicle');//->middleware('perm:1');
    Route::post('/vehicles/new','VehicleController@new_vehicle');//->middleware('perm:1');
    Route::post('/vehicles/update','VehicleController@update_vehicle')->middleware('perm:1');
    Route::post('/vehicles/assign_driver','VehicleController@assign_driver')->middleware('perm:1');
    Route::get('/vehicles/revoke_driver/{vehicle_id}/{driver_id}','VehicleController@revoke_driver')->middleware('perm:1');
    Route::post('/vehicles/blacklist','VehicleController@blacklist_vehicle')->middleware('perm:1');



    Route::get('/users', 'UserController@index')->middleware('perm:1');
    Route::get('/users/delete/{user_id}', 'UserController@delete_user')->middleware('perm:1');
    Route::post('/enroll', 'UserController@register_user')->middleware('perm:1');
    Route::get('/users/profile/{user_id}', 'UserController@profile')->middleware('perm:1');
    Route::post('/users/profile/update', 'UserController@update')->middleware('perm:1');


    Route::post('/orders/new','OrderController@new_order');//->middleware('perm:1');




});





