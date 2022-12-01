<?php

namespace App\Http\Controllers\Api\V4;

use App\Http\Controllers\Controller;
use App\Models\english_video\Product;
use App\Models\Pet;
use App\Models\V1_leonid_model\User;
use App\Models\V4\Cards;
use App\Models\V4\Register;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CardsController extends Controller
{


    public function create_cards(Request $request)
    {
        $user = Auth::user()->makeHidden(['id', 'role']);
        if ($user->role == 3) {//Может изменить только администратор или доставщик данного заказа
            $cards = Cards::where('id_User', 'like', $user->id)->get();
            $result = [];
            foreach ($cards as $cards)
            {
                $search_cards = [
                    'id' => $cards->id,
                    'number' => $cards->number,
                    'date_exit' => $cards->date_exit,
                    'cvv' => $cards->cvv,
                    'name' => $cards->name

                ];
                array_push($result, $search_cards);
            }
            if($result==null){
                $validation = Validator::make($request->all(), [
                    'name' => 'required|regex:/^[А-ЯЁа-яё -]/u',
                    'number' => 'required|regex:/^[0-9]/',
                    'date_exit' => 'required|regex:/^[а-яА-ЯёЁa-zA-Z0-9]+$/u',
                    'cvv' => 'required|regex:/^[1-9+]+$/',
                ]);
                if ($validation->fails()) {
                    $result = ['error' => ['code' => 400, 'message' => 'Validation error', 'error' => $validation->errors()]];
                    return response($result, 400);
                }
                $users = Cards::create([
                    'id_User' => $user->id,
                    'name' => $request->name,
                    'number' => $request->number,
                    'date_exit' => $request->date_exit,
                    'cvv' => $request->cvv,
                ]);
                return response(['data' => ['order' => $users]], 200);
            }
            else
            {
                return response(['data' => ['status' => 'Оппа', 'Ситуация'=>'Вы уже добавили карту']], 418);

            }

        }
        else
        {
            return response(['data' => ['status' => 'Error', 'Ситуация'=>'Вам нужно быть обычным пользователем']], 403);
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

