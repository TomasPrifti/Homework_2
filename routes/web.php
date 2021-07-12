<?php

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

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
    return redirect("home");
});


Route::get('/home', function () {
    return view('home');
});

/* Routes Restaurants */
Route::get('/restaurants', 'ControllerRestaurants@returnView');
Route::get('/restaurants/addRestaurants', 'ControllerRestaurants@addRestaurants');
Route::get('/restaurants/deleteProfile', 'ControllerRestaurants@deleteProfile');
Route::get('/restaurants/logout', 'ControllerRestaurants@logout');
Route::post('/restaurants/update', 'ControllerRestaurants@update')->name('form_update');
Route::post('/restaurants/login', 'ControllerRestaurants@login')->name('form_login');
Route::post('/restaurants/register', 'ControllerRestaurants@register')->name('form_register');

/* Routes Suppliers */
Route::get('/suppliers', 'ControllerSuppliers@returnView');
Route::get('/suppliers/addSuppliers', 'ControllerSuppliers@addSuppliers');
Route::get('/suppliers/deleteProfile', 'ControllerSuppliers@deleteProfile');
Route::get('/suppliers/logout', 'ControllerSuppliers@logout');
Route::post('/suppliers/update', 'ControllerSuppliers@update')->name('form_update');
Route::post('/suppliers/login', 'ControllerSuppliers@login')->name('form_login');
Route::post('/suppliers/register', 'ControllerSuppliers@register')->name('form_register');

/* Routes Products */
Route::get('/products', 'ControllerProducts@returnView');
Route::get('/products/api1/{type}/{data?}', 'ControllerProducts@api1');
Route::get('/products/api2', 'ControllerProducts@api2');
Route::get('/products/logout', 'ControllerProducts@logout');
Route::post('/products/addPreference', 'ControllerProducts@addPreference')->name('form_preference');
Route::get('/products/showPreference', 'ControllerProducts@showPreference');
Route::post('/products/removePreference', 'ControllerProducts@removePreference');
