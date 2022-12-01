<?php


use App\Http\Controllers\Api\V4\CardsController;
use App\Http\Controllers\Api\V4\ProductController;
use App\Http\Controllers\Api\V4\RegistrController;
use App\Http\Controllers\Api\V4\RegistrrController;
use App\Http\Controllers\Api\V4\RegistrrrController;
use App\Http\Controllers\Api\V4\RegistrDostavhikController;
use App\Http\Controllers\Api\V4\ZakazController;
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

/*
//1//Route::post('/register', [RegistrController::class,'create31']);
//2//Route::post('/login',[RegistrController::class,'login']);
//3//Route::get('/get_dostavhuk', [RegistrController::class,'get_dostavhuk'])->middleware('auth:api');
//4//Route::get('/get_polzovatel', [RegistrController::class,'get_polzovatel'])->middleware('auth:api');
//5//Route::get('/get_users', [RegistrController::class,'get'])->middleware('auth:api');
//6//Route::post('/update_polzovatel', [RegistrController::class,'update_polzovatel_role_admin'])->middleware('auth:api');
//7//Route::post('/create_cards', [CardsController::class,'create_cards'])->middleware('auth:api');
//8//Route::post('/create_product', [ProductController::class,'create_product_1'])->middleware('auth:api');
//9//Route::get('/get_product', [ProductController::class,'get']);
//10//Route::get('/search_product', [ProductController::class,'search_product']);
//11//Route::delete('/delete_product_photo', [ProductController::class,'delete_photo_product'])->middleware('auth:api');
//12//Route::delete('/delete_product/{id}', [ProductController::class,'delete_product'])->middleware('auth:api');
//13//Route::post('/create_zakaz', [ZakazController::class,'create_zakaz'])->middleware('auth:api');
//14//Route::post('/update_zakaz/{id}', [ZakazController::class,'update_zakaz_admin'])->middleware('auth:api');
//15//Route::post('/edit_status_zakaz/{id}', [ZakazController::class,'edit_status_zakaz'])->middleware('auth:api');
//16//oute::get('/search_zakaz_d', [ZakazController::class,'search_zakaz_dostavhuk'])->middleware('auth:api');
//17//Route::get('/search_zakaz_a', [ZakazController::class,'search_zakaz_admin'])->middleware('auth:api');
//18//Route::get('/search_zakaz_p', [ZakazController::class,'search_zakaz_polzovatel'])->middleware('auth:api');
*/



//ПОЛЬЗОВАТЕЛЬ//
//Регистрация
/*1,3*/Route::post('/register', [RegistrController::class,'create']);
//Вход в аккаунт
/*2*/Route::post('/login',[RegistrController::class,'login']);
/*4*/Route::get('/get_users', [RegistrController::class,'get'])->middleware('auth:api');
/*10*/Route::patch('/update_polzovatel/{id}', [RegistrController::class,'update_polzovatel_role_admin'])->middleware('auth:api');
/*16*/Route::get('/get_polzovatel', [RegistrController::class,'get_polzovatel'])->middleware('auth:api');
/*17*/Route::get('/get_dostavhuk', [RegistrController::class,'get_dostavhuk'])->middleware('auth:api');

///Вывод всех пользвателей


//ДОСТАЩИК//
//Вывод всех доставщиков
/*3*///Route::get('/get_dostavhuk', [RegistrDostavhikController::class,'get'])->middleware('auth:api');
//Регистрация
/*
Route::post('/register_d', [RegistrDostavhikController::class,'create_d'])->middleware('auth:api');
Route::post('/login_dostavhuk', [RegistrDostavhikController::class,'login']);
*/


//КАРТА//
//Добавление карты
/*7*/Route::post('/create_cards', [CardsController::class,'create_cards'])->middleware('auth:api');

//ПРОДУКТЫ//
//Создание продукта
/*3*/Route::post('/create_product', [ProductController::class,'create_product'])->middleware('auth:api');
//Вывод всей продукции в порядке убывания по id
/*5*/Route::get('/get_product', [ProductController::class,'get']);
//Поиск по названию категории(сделан через like поэтому даже часть слова подойдет)
/*6*/Route::get('/search_product', [ProductController::class,'search_product']);
/*12*/Route::delete('/delete_product_photo', [ProductController::class,'delete_photo_product'])->middleware('auth:api');
/*13*/Route::delete('/delete_product/{id}', [ProductController::class,'delete_product'])->middleware('auth:api');



//ЗАКАЗ//
//Создание заказа
/*8*/Route::post('/create_zakaz', [ZakazController::class,'create_zakaz'])->middleware('auth:api');
/*9*/Route::post('/update_zakaz/{id}', [ZakazController::class,'update_zakaz_admin'])->middleware('auth:api');
/*11*/Route::post('/edit_status_zakaz/{id}', [ZakazController::class,'edit_status_zakaz'])->middleware('auth:api');
/*14*/Route::get('/search_zakaz_d', [ZakazController::class,'search_zakaz_dostavhuk'])->middleware('auth:api');
/*15*/Route::get('/search_zakaz_a', [ZakazController::class,'search_zakaz_admin'])->middleware('auth:api');
/*18*/Route::get('/search_zakaz_p', [ZakazController::class,'search_zakaz_polzovatel'])->middleware('auth:api');
