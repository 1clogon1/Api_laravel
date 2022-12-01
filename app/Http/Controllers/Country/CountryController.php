<?php

namespace App\Http\Controllers\Country;


use App\Http\Controllers\Controller;
use App\Models\video_1\CountryModel;
use http\Env\Request;

class CountryController extends Controller
{
    //Метод, который будет возвращать записи из таблицы Country_length
    public function country(){
        return response()->json(CountryModel::get(),200);//Имя нашей модели - CountryModel
    }

    public function countryById($id){//$id - принимаемый параметр
        return response()->json(CountryModel::find($id),200);//find - статический метод, через который делается запрос к базе для поиска этого id($id)
    }

    public function countrySave(Request $req){//Создание записи
        $country = CountryModel::create($req->all());
        return response()->json($country,201);
    }

    public function countryEdit(Request $req, CountryModel $country){//Изменения записи
        $country -> update($req->all());
        return response()->json($country,200);
    }
//    function add(Request $req){
//        $country = new CountryModel;
//        $country ->alias=$req->alias;
//        $country ->name=$req->name;
//        $country ->name_en=$req->name_en;
//        $result = $country->save();
//        if($result){
//            return ["Result"=>"Data as been saved"];
//        }
//        else{
//            return ["Result"=>"operation failed"];
//
//        }
//    }
}















