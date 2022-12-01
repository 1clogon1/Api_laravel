<?php

namespace App\Http\Controllers\Api\V4;


use App\Http\Controllers\Controller;
use App\Models\V4\Register;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RegistrController extends Controller
{

    public function create(Request $request)
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
        ]);
        if ($validation->fails())
        {
            $result = ['error' => ['code' => 400, 'message' => 'Validation error', 'error' => $validation->errors()]];
            return response($result, 400);
        }
        else{
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
                'role' => '3',
                'api_token' => Str::random(80)
            ]);
            return response(['Событие'=>'Успешная регистрация', 'id'=>$user->id], 200);
        }


    }


    /*public function create41(Request $request)
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
        return response(['data'=>['status'=>'OK', 'id'=>$user->id]], 204);
    }*/


    /*public function post(Request $request): JsonResponse
    {
        if ($this->isAdmin($request->user())) {
            $RegisterPolzovat=Register::orderBy('id','DESC')->get();
            return response()->json($RegisterPolzovat);
        }
        return $this->onError(401, 'Unauthorized Access');
    }*/

    public function get(Request $request)
    {
        $user=Auth::user()->makeHidden(['role']);
        if($user->role==1)
        {
            $RegisterPolzovat=Register::orderBy('id','DESC')->get();
            return response()->json($RegisterPolzovat);
        }
        else
        {
            return response(['data' => ['status' => 'Error', 'Ситуация'=>'Недостаточно прав']], 403);
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
   /* public  function login1(Request $request)
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
    }*/
    public function get_dostavhuk() {
        $user=Auth::user()->makeHidden(['role']);
        if($user->role==1)
        {
        $dostavhuk=Register::where('role', 'like', '4')->get();
        $result=[];
        foreach ($dostavhuk as $dostavhuk){
            $search_product=[
                'id' => $dostavhuk->id,
                'name' => $dostavhuk->name,
                'sure_name' => $dostavhuk->sure_name,
                'othestvo' => $dostavhuk->othestvo,
                'email' => $dostavhuk->email,
                'phone' => $dostavhuk->phone,
                'data_rojden' => $dostavhuk->data_rojden,
                'pol' => $dostavhuk->pol,
                'adres' => $dostavhuk->adres,
                'role' => $dostavhuk->role,
            ];
            array_push($result, $search_product);
        }
        return response(['data'=>['order'=>$result]], 200);
        }
        else
        {
            return response(['data' => ['status' => 'Error', 'Ситуация'=>'Недостаточно прав']], 403);
        }
    }
    public function get_polzovatel() {
        $user=Auth::user()->makeHidden(['role']);
        if($user->role==1)
        {
        $users=Register::where('role', 'like', '3')->get();
        $result=[];
        foreach ($users as $users){
            $search_product=[
                'id' => $users->id,
                'name' => $users->name,
                'sure_name' => $users->sure_name,
                'othestvo' => $users->othestvo,
                'email' => $users->email,
                'phone' => $users->phone,
                'data_rojden' => $users->data_rojden,
                'pol' => $users->pol,
                'adres' => $users->adres,
                'role' => $users->role,
            ];
            array_push($result, $search_product);
        }
        return response(['data'=>['order'=>$result]], 200);
        }
        else
        {
            return response(['data' => ['status' => 'Error', 'Ситуация'=>'Недостаточно прав']], 403);
        }
    }

    public function login(Request $request){
        $validation=Validator::make($request->all(), [
            'password'=>'required',
            'email'=>'required'
        ]);
        if ($validation->fails()){
            $result=[
                'error'=>[
                    'code'=>400,
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
                    'ОУ у тебя произошла ошибка']]
        ]);
    }


    public function update_polzovatel_role_admin(Request $request, $id)
    {
        $user = Auth::user()->makeHidden(['id', 'role']);
        if ($user->role == 1) {//Может изменить только администратор
            $users = Register::whereId($id)->first();

            $validation = Validator::make($request->all(),
                [
                    'role' => 'required|regex:/^[0-9+]+$/',
                ]);
            if ($validation->fails()) return response(['error' => ['code' => 400, 'message' => 'Validation error', 'error' => $validation->errors()]], 400);
            /*if ($request->Text('ID_Dostavhuk')) {
                $zakaz->update(['ID_Dostavhuk' => $request->ID_Dostavhuk]);
            }*/
            $users->role = $request->role;

            $users->update($request->input());
            return response(['data' => ['status' => 'OK']], 200);
        }
        else
        {
            return response(['error' => ['status' => 'Ошибка', 'Ситуация'=>'Недостаточно прав']], 403);
        }
    }


}

