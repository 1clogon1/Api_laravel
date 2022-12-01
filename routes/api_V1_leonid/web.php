<?php

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

Route::get('/', function () {return view('welcome');});
Route::get('/pets/slider', 'App\Http\Controllers\PetController@slider');
Route::get('/search/', 'App\Http\Controllers\PetController@search');
Route::get('/pets', 'App\Http\Controllers\PetController@pets');
Route::post('/subscription', 'App\Http\Controllers\SubscriptionController@email');
Route::post('/register', 'App\Http\Controllers\UserController@create');
Route::post('/login', 'App\Http\Controllers\UserController@login');
Route::get('/search/order', 'App\Http\Controllers\PetController@order');
Route::get('/users', 'App\Http\Controllers\UserController@show')->middleware('auth:api');
Route::patch('/users/phone', 'App\Http\Controllers\UserController@phone')->middleware('auth:api');
Route::patch('/users/email', 'App\Http\Controllers\UserController@email')->middleware('auth:api');
Route::get('/users/orders', 'App\Http\Controllers\PetController@show_pets')->middleware('auth:api');
Route::delete('/users/orders/{id}', 'App\Http\Controllers\PetController@destroy')->middleware('auth:api');
Route::post('/pets/{id}', 'App\Http\Controllers\PetController@update')->middleware('auth:api');
Route::get('/pets/{id}', 'App\Http\Controllers\PetController@show');
Route::post('/pets/', 'App\Http\Controllers\PetController@create');
