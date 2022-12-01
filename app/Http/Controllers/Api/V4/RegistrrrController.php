<?php

namespace App\Http\Controllers\Api\V4;

use App\Http\Library\ApiHelpers;
use App\Http\Controllers\Controller;
use App\Models\english_video\Product;
use App\Models\Pet;
use App\Models\User;
use App\Models\V4\Register;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RegistrrrController extends Controller
{

    public function create31(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|regex:/^[А-ЯЁа-яё -]+$/u',
            'sure_name' => 'required|regex:/^[А-ЯЁа-яё -]+$/u',
            'othestvo' => 'required|regex:/^[А-ЯЁа-яё -]+$/u',
            'email' => 'required|email|unique:users',
            'phone' => 'required|regex:/^[0-9+]+$/',
            'password' => 'required|regex:/^(?=.*[A-Z])(?=.*[\d])(?=.*[a-z])[a-zA-Z\d]{7,}$/',
            'password_confirmation' => 'same:password',
            'data_rojden' => 'required|date',
            'pol' => 'required|regex:/^[А-ЯЁа-яё -]+$/u',
            'adres' => 'required|regex:/^[А-ЯЁа-яё -]+$/u',
            'password_recovery' => 'required|regex:/^[0-9+]+$/',
            'role' => 'required|regex:/^[0-9+]+$/'
        ]);
        if ($validation->fails()) {
            $result = ['error' => ['code' => 422, 'message' => 'Validation error', 'error' => $validation->errors()]];
            return response($result, 422);
        }

        $user=Register::create([
            'name' => $request->name,
            'sure_name' => $request->sure_name,
            'othestvo' => $request->othestvo,
            'email' => $request->email,
            'phone' => $request->phone,
            'data_rojden' => $request->data_rojden,
            'pol' => $request->pol,
            'adres' => $request->adres,
            'password_recovery' => $request->password_recovery,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'api_token' => Str::random(80)
        ]);
        return response(['data'=>['status'=>'OK', 'id'=>$user->id]], 204);
        //return response('Успешная регистрация', 204);

    }


    public function create41(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|regex:/^[А-ЯЁа-яё -]+$/u',
            'sure_name' => 'required|regex:/^[А-ЯЁа-яё -]+$/u',
            'othestvo' => 'required|regex:/^[А-ЯЁа-яё -]+$/u',
            'email' => 'required|email|unique:users',
            'phone' => 'required|regex:/^[0-9+]+$/',
            'password' => 'required|regex:/^(?=.*[A-Z])(?=.*[\d])(?=.*[a-z])[a-zA-Z\d]{7,}$/',
            'password_confirmation' => 'same:password',
            'data_rojden' => 'required|date',
            'pol' => 'required|regex:/^[А-ЯЁа-яё -]+$/u',
            'adres' => 'required|regex:/^[А-ЯЁа-яё -]+$/u',
            'password_recovery' => 'required'
        ]);
        if ($validation->fails()) {
            $result = ['error' => ['code' => 422, 'message' => 'Validation error', 'error' => $validation->errors()]];
            return response($result, 422);
        }

        $user=Register::create([
            'name' => $request->name,
            'sure_name' => $request->sure_name,
            'othestvo' => $request->othestvo,
            'email' => $request->email,
            'phone' => $request->phone,
            'data_rojden' => $request->data_rojden,
            'pol' => $request->pol,
            'adres' => $request->adres,
            'password_recovery' => $request->password_recovery,
            'password' => Hash::make($request->password),
            'api_token' => Str::random(80)
        ]);
        return response('Успешная регистрация', 204);

    }


    /*public function post(Request $request): JsonResponse
    {
        if ($this->isAdmin($request->user())) {
            $RegisterPolzovat=Register::orderBy('id','DESC')->get();
            return response()->json($RegisterPolzovat);
        }
        return $this->onError(401, 'Unauthorized Access');
    }*/
    public function phone(Request $request){

        $user=Auth::user();
        $validation=Validator::make($request->all(), ['phone'=>'required']);
        if ($validation->fails()){
            return response(['error'=>['code'=>422, 'message'=>'Validation error', 'error'=>$validation->errors()]], 422);
        }
        $user->phone=$request->phone;
        $user->save();
        return response(['data'=>['status'=>'OK']], 200);
    }

    public function get(Request $request)
    {
        $user=Auth::user()->makeHidden(['role']);
        if($user->role==1)
        {
            $RegisterPolzovat=Register::orderBy('id','DESC')->get();
            return response()->json($RegisterPolzovat);
        }

    }
    /*public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('appToken')->accessToken;
            //After successfull authentication, notice how I return json parameters
            return response()->json([
                'success' => true,
                'token' => $success,
                'user' => $user
            ]);
        } else {
            //if authentication is unsuccessfull, notice how I return json parameters
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }
    }*/
    /*public  function registerr(Request $request){//Метод регистрации с обязательными полями
        $fields = $request->validate([
            'name' => 'required|regex:/^[А-ЯЁа-яё -]+$/u',
            'sure_name' => 'required|regex:/^[А-ЯЁа-яё -]+$/u',
            'othestvo' => 'required|regex:/^[А-ЯЁа-яё -]+$/u',
            'email' => 'required|email|unique:users',
            'phone' => 'required|regex:/^[0-9+]+$/',
            'password' => 'required|regex:/^(?=.*[A-Z])(?=.*[\d])(?=.*[a-z])[a-zA-Z\d]{7,}$/',
            'password_confirmation' => 'same:password',
            'data_rojden' => 'required|date',
            'pol' => 'required|regex:/^[А-ЯЁа-яё -]+$/u',
            'adres' => 'required|regex:/^[А-ЯЁа-яё -]+$/u',
            'password_recovery' => 'required'
        ]);


        //Создание пользователь
        $user = Register::create([
            'name' => $fields['name'],
            'sure_name' => $fields['sure_name'],
            'password' => bcrypt($fields['password']),//bcrypt - для хеширования пароля
            'othestvo' => $fields['othestvo'],
            'data_rojden' => $fields['data_rojden'],
            'pol' => $fields['pol'],
            'email' => $fields['email'],
            'adres' => $fields['adres'],
            'password_recovery' => $fields['password_recovery'],
            'phone' => $fields['phone'],
            'api_token' => Str::random(80),
        ]);

        //Создание токена
        $token = $user->createToken('myapptoken')->plainTextToken;//plainTextToken - получаем токен в текстовом виде

        //Ответ
        $response =[
            'user' => $user,//Информация о пользователе
            'api_token' => $token//Токен
        ];

        //Возврат
        return response($response,201);
    }*/
    public  function login1(Request $request)
    {//Вход в систему
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        //Проверка почты
        $user = Register::where('email', $fields['email'])->first();//Если почта существует, то она помещается в $user

        //Проверка почты
        if (!$user || !Hash::check($fields['password'], $user->password)) {//!user - проверка на то существует ли пользователь, $Hash -проверка пароля
            return response([
                'code' => 401,
                'messege' => 'Bad creds'
            ], 401);
        }
    }



    public function login(Request $request){
        $validation=Validator::make($request->all(), ['password'=>'required', 'email'=>'required']);
        if ($validation->fails()){
            $result=['error'=>['code'=>422, 'message'=>'Validation error', 'error'=>$validation->errors()]];
            return response($result, 422);
        }

        if (Auth::once($request->input())){
            $user=Auth::user();
            return response(['data'=>['token'=>$user->api_token]], 200);
        }
        return response(['error'=>['code'=>401, 'message'=>'Unauthorized', 'error'=>['phone'=>'phone or password incorrect']]]);
    }


    public function login3(Request $request){
        $validation=Validator::make($request->all(), [
            'password'=>'required',
            'email'=>'required'
        ]);
        if ($validation->fails()){
            $result=[
                'error'=>[
                    'code'=>422,
                    'message'=>'Validation error',
                    'error'=>$validation->errors()
                ]];
            return response($result, 422);
        }

        if (Auth::once($request->input())){
            $user=Auth::user();

            return response([
                'data'=>[
                    'token'=>$user->api_token
                ]], 200);
        }
        return response([
            'error'=>[
                'code'=>401,
                'message'=>'Unauthorized',
                'error'=>[
                    'phone'=>'ОУ у тебя произошла ошибка']]
        ]);
    }


    public function logout (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }


    public function logout1(Request $res)
    {
        if (Auth::user()) {
            $user = Auth::user()->token();
            $user->revoke();

            return response()->json([
                'success' => true,
                'message' => 'Logout successfully'
            ]);
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Unable to Logout'
            ]);
        }
    }



}

