<?php

//use App\Http\Controllers\Api\V1\DeskController2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Связь с функциями
//Связь с функциями

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
////Получение
//Route::get('country', 'App\Http\Controllers\Country\CountryController@country');//Для получения всех записей//country - параметр /CountryController - имя вызываемого контроллера //@country - имя метода//С 8 версии нужно указывать полный путь (App\Http\Controllers\Country\CountryController@country), а не укороченный как раньше(Country\CountryController@country)
//Route::get('country/{id}', 'App\Http\Controllers\Country\CountryController@countryById');//Для получения определенной записи по id
//
////Отправка
//Route::post('countrySav', 'App\Http\Controllers\Country\CountryController@countrySave');//Для отправки данных в базу данных
//Route::post('add', 'App\Http\Controllers\Country\CountryController@add');//Для отправки данных в базу данных
//
//Route::put('country/{country}', 'App\Http\Controllers\Country\CountryController@countryEdit');

//Route - маршруты для api

//Route::get('/desks',[DeskController::class,'index']);//[DeskController::class,'index'] - массив для вызова DeskController //index - метод который мы будем вызывать
//Route::get('desks', 'App\Http\Controllers\Api\DeskController@index');//Для получения всех записей//country - параметр /CountryController - имя вызываемого контроллера //@country - имя метода//С 8 версии нужно указывать полный путь (App\Http\Controllers\Country\CountryController@country), а не укороченный как раньше(Country\CountryController@country)
//Route::get('desks/{id}', 'App\Http\Controllers\Api\DeskController@indexID');
//Route::post('deskSave', 'App\Http\Controllers\Api\DeskController@deskSave');//Для отправки данных в базу данных
//Route::put('deskEdit/{deskc}', 'App\Http\Controllers\Api\DeskController@deskEdit');


Route::apiResources([
    'desks' => DeskController2::class,

]);


































