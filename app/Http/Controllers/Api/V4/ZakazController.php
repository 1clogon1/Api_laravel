<?php

namespace App\Http\Controllers\Api\V4;

use App\Http\Library\ApiHelpers;
use App\Http\Controllers\Controller;
use App\Models\english_video\Product;
use App\Models\Pet;
use App\Models\User;
use App\Models\V4\Register;
use App\Models\V4\Zakaz;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ZakazController extends Controller
{
    public function create_zakaz(Request $request)
    {
        $user = Auth::user()->makeHidden(['id', 'name','sure_name','othestvo','phone','adres']);
        if ($user->role == 3) {//Может заказать только зарегистрированный обычный пользователь
            $validation = Validator::make($request->all(), [
                'from_where_orders' => 'required',
                'Description_product' => 'required',
                'Description_User' => 'required',

            ]);
            if ($validation->fails()) {
                $result = ['error' => ['code' => 400, 'message' => 'Validation error', 'error' => $validation->errors()]];
                return response($result, 400);
            }

           $zakaz= Zakaz::create([
                'from_where_orders' => $request->from_where_orders,
                'where_to_deliver' => 'ул. Оптиков, 46к1, 46, к.1, Санкт-Петербург',
                'User_data' =>$user->name.' '.$user->sure_name.' '.$user->othestvo.'; '.'\n;'.'Телефон: '.$user->phone.'\n; '.'Адрес: '.$user->adres.'\n;',
                'Description_product' => $request->Description_product,
                'Description_User' => $request->Description_User,
                'Status' => 'Обработка',//Начальные значение
                'ID_User'=> $user->id,
                'All_Price' => '3000',
               'Time' => '00:23:00',
               'Input' => '0',
            ]);
            return response(['data'=>['Событие'=>'Успешное оформление заказа', 'id'=>$zakaz->id]], 200);
        }
        else
        {
            return response(['data' => ['status' => 'Error', 'Ситуация'=>'Недостаточно прав']], 403);
        }
    }


    public function update_zakaz_admin(Request $request, $id)
    {


        $user = Auth::user()->makeHidden(['id', 'role']);
        if ($user->role == 1) {//Может изменить только администратор

            $validation = Validator::make($request->all(),
                [
                    'ID_Dostavhuk' => 'required|regex:/^[0-9+]+$/',
                ]);
            if ($validation->fails()) return response(['error' => ['code' => 400, 'message' => 'Validation error', 'error' => $validation->errors()]], 400);

            $dostavhuk= Register::whereId($request->ID_Dostavhuk)->first();//Для проверки доставщика и его статуса
            if ($dostavhuk->role == 4)
            {//Можно изменить только пользователя с ролью доставщик
                $zakaz = Zakaz::whereId($id)->first();
                if ($zakaz === null) return response(['error' => ['code' => 404, 'message' => "Order doesn't exist"]], 404);
                    if ($zakaz->Status !== 'Доставлен')
                    {

                        $zakaz->ID_Dostavhuk = $request->ID_Dostavhuk;

                        $zakaz->update($request->input());
                        return response(['data' => ['status' => 'Доставщик изменен', 'ID_Dostavhuk' => $zakaz->ID_Dostavhuk]], 200);
                    }
                    else
                    {
                        return response(['error' => ['code' => 418, 'message' => 'Заказ уже доставлен']], 418);//Ты чайник
                    }
               }
                else
                {
                    return response(['error' => ['status' => 'Ошибка', 'Ситуация'=>'"Это не доставщик"']], 422);
                }

        }
        else
        {
            return response(['error' => ['status' => 'Ошибка', 'Ситуация'=>'Недостаточно прав']], 403);
        }
    }

    public function edit_status_zakaz(Request $request, $id)
    {
        //Данные заказа
        $zakaz = Zakaz::whereId($id)->first();

        //if ($zakaz === null) return response(['error' => ['code' => 404, 'message' => "Order doesn't exist"]], 404);
        //Данные редактора(администратор или доставщик)
        $user = Auth::user()->makeHidden(['id', 'role']);
        if (($user->id == $zakaz->ID_Dostavhuk &&$user->role ==4)|| $user->role == 1) {//Может изменить только администратор или доставщик данного заказа

            if ($zakaz->Status !== 'Доставлен')
            {
                $validation = Validator::make($request->all(),
                    [
                        'Status' => 'required|regex:/^[А-ЯЁа-яё -]+$/u',
                    ]);
                if ($validation->fails()) return response(['error' => ['code' => 400, 'message' => 'Validation error', 'error' => $validation->errors()]], 400);
                $zakaz->Status = $request->Status;//Передаем новый статус

                $zakaz->update($request->input());
                return response(['data' => ['message' => 'Статус заказа обновлен','Статус'=>$zakaz->Status]], 200);
            }
            else
            {
               return response(['error' => ['code' => 418, 'message' => 'Заказ уже доставлен']], 418);//Ты чайник
            }
        }
        else
        {
            return response(['data' => ['status' => 'Error', 'Ситуация'=>'Недостаточно прав']], 403);
        }
    }


    public function search_zakaz_dostavhuk()
    {
        $user = Auth::user()->makeHidden(['id', 'role']);
        if ($user->role == 4) {//Может изменить только администратор или доставщик данного заказа
            //$zakaz = Zakaz::where('ID_Dostavhuk', 'like', '4')->first();
           $zakaz = Zakaz::where('ID_Dostavhuk', 'like', $user->id)->get();
                $result = [];
                foreach ($zakaz as $zakaz)
                {
                    $search_zakaz = [
                        'id' => $zakaz->id,
                        'from_where_orders' => $zakaz->from_where_orders,
                        'where_to_deliver' => $zakaz->where_to_deliver,
                        'User_data' => $zakaz->User_data,
                        'Description_product' => $zakaz->Description_product,
                        'Description_User' => $zakaz->Description_User,
                        'All_Price' => $zakaz->All_Price,
                        'Status' => $zakaz->Status,
                        'Date' => $zakaz->Date,
                        'Time' => $zakaz->Time

                    ];
                    array_push($result, $search_zakaz);
                }
                if($result==null){
                    return response(['data' => ['status' => 'Оппа', 'Ситуация'=>'У вас нету не выполненных заказов']], 418);
                }
                else
                {
                    return response(['data' => ['order' => $result]], 200);
                }

        }
        else
        {
            return response(['data' => ['status' => 'Error', 'Ситуация'=>'Недостаточно прав']], 403);
        }
    }


    public function search_zakaz_polzovatel()
    {
        $user = Auth::user()->makeHidden(['id', 'role']);
        if ($user->role == 3) {
            $zakaz = Zakaz::where('ID_User', 'like', $user->id)->get();
            $result = [];
            foreach ($zakaz as $zakaz)
            {
                $search_zakaz = [
                    'id' => $zakaz->id,
                    'from_where_orders' => $zakaz->from_where_orders,
                    'where_to_deliver' => $zakaz->where_to_deliver,
                    'User_data' => $zakaz->User_data,
                    'Description_product' => $zakaz->Description_product,
                    'Description_User' => $zakaz->Description_User,
                    'All_Price' => $zakaz->All_Price,
                    'Status' => $zakaz->Status,
                    'Date' => $zakaz->Date,
                    'Time' => $zakaz->Time

                ];
                array_push($result, $search_zakaz);
            }
            if($result==null){
                return response(['data' => ['status' => 'Оппа', 'Ситуация'=>'У вас нету заказов']], 418);
            }
            else
            {
                return response(['data' => ['order' => $result]], 200);
            }

        }
        else
        {
            return response(['data' => ['status' => 'Error', 'Ситуация'=>'Недостаточно прав']], 403);
        }
    }

    public function search_zakaz_admin()
    {
        $user = Auth::user()->makeHidden(['id', 'role']);
        if ($user->role == 1) {//Может изменить только администратор или доставщик данного заказа
            $zakaz = Zakaz::where('Status', 'not like', 'Доставлен')->get();
            $result = [];
            foreach ($zakaz as $zakaz)
            {
                $search_zakaz = [
                    'id' => $zakaz->id,
                    'from_where_orders' => $zakaz->from_where_orders,
                    'where_to_deliver' => $zakaz->where_to_deliver,
                    'User_data' => $zakaz->User_data,
                    'Description_product' => $zakaz->Description_product,
                    'Description_User' => $zakaz->Description_User,
                    'All_Price' => $zakaz->All_Price,
                    'Status' => $zakaz->Status,
                    'Date' => $zakaz->Date,
                    'Time' => $zakaz->Time

                ];
                array_push($result, $search_zakaz);
            }
            if($result==null){
                return response(['data' => ['status' => 'Оппа', 'Ситуация'=>'У вас нету не выполненных заказов']], 418);
            }
            else
            {
                return response(['data' => ['order' => $result]], 200);
            }
        }
        else
        {
            return response(['data' => ['status' => 'Error', 'Ситуация'=>'Недостаточно прав']], 403);
        }
    }


}

