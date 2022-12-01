<?php

use App\Http\Controllers\Api\V3\AuntController;
use App\Http\Controllers\Api\V3\ProductController;
use App\Http\Controllers\Api\V3\ImageCrudController;
use App\Http\Controllers\Api\V3\otdelnoController;
use App\Http\Controllers\Api\V3\UserController;
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
/////////////////////////////////
//Общедоступные маршруты
//Route::get('/products', function (){//С помощью таких запросов можно к примеру возвращать инструкцию с возможными get или post запросами
//    return 'products';
//});
//Route::resource('products',ProductController::class);//Общий маршрут//Для получения стразу всех маршрутов, то есть в нем содержаться все маршруты которые могут быть в ProductController в стандартном виде, и в место того, чтобы добавлять обычные Route с get, post, put и т.д.
Route::get('/products', [ProductController::class, 'index' ]);//С помощью таких запросов можно к примеру возвращать инструкцию с возможными get или post запросами
Route::get('/products/{id}', [ProductController::class, 'show' ]);
Route::get('/products/search/{name}', [ProductController::class, 'search' ]);//Для поиска по имени будет нужен свой маршрут

Route::get('/show2/{id}', [ProductController::class, 'show2' ]);//С помощью таких запросов можно к примеру возвращать инструкцию с возможными get или post запросами

Route::post('/register',[AuntController::class,'register']);//Регистрация
Route::post('/login',[ProductController::class,'login_2']);//Вход



//Мое и видео
Route::post('/create_img_2',[ImageCrudController::class,'create_img_2']);//Фото вывод
Route::get('/image_get',[ImageCrudController::class,'index_']);//Фото создание
Route::put('/image_put/{id}',[ImageCrudController::class,'update_']);//Фото создание
//Route::get('/products/search/{name}', [ProductController::class, 'search' ]);//Для поиска по имени будет нужен свой маршрут
//////////////////////////////////
//Видео
Route::post('/create',[ImageCrudController::class,'create']);
Route::get('/get',[ImageCrudController::class,'get']);
Route::patch('/edit/{id}',[ImageCrudController::class,'edit']);
Route::post('/update/{id}',[ImageCrudController::class,'update']);
Route::delete('/delete/{id}',[ImageCrudController::class,'destroy']);
//////////////////////////////////
//Защищённые маршруты - для зарегистрированных пользователей
Route::group(['middleware' => ['auth:sanctum']], function () {//Маршрут для токена взят из документации, к примеру в данном случае мы защитили маршрут файлов и без токена мы не сможем увидеть поиск
    Route::post('/products',[ProductController::class,'store']);//Добавление
    Route::put('/products/{id}',[ProductController::class,'update']);//Обновление
    Route::delete('/products/{id}',[ProductController::class,'destroy']);//Удаление
    Route::post('/logout',[AuntController::class,'logout']);//Выход из системы
});





//Route::post('/register', 'App\Http\Controllers\Api\V3\UserController@create');

Route::get('/pets', [ProductController::class,'pets33']);///////////////////////////
Route::post('/create2', [ProductController::class,'create2']);///////////////////////////
Route::post('/create123', [ProductController::class,'create3']);///////////////////////////
Route::post('/pets12', 'App\Http\Controllers\PetController@create');
Route::post('/login2', [ProductController::class,'login2']);
Route::get('/pets2/{id}', [ProductController::class,'show2'])->middleware('auth:api');
Route::get('/search2/{description}',[ProductController::class,'search2']);
Route::get('/search1/{description}',[ProductController::class,'search']);
Route::patch('/users/phone', [ProductController::class,'phone'])->middleware('auth:api');
