<?php

namespace App\Http\Controllers\Api\V4;

use App\Http\Controllers\Controller;
use App\Models\english_video\Product;
use App\Models\Pet;
use App\Models\V1_leonid_model\User;
use App\Models\V4\Register;
use App\Models\V4\RegisterDostavhuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RegistrDostavhikController extends Controller
{


    public function create_d(Request $request)
    {
        $user=Auth::user()->makeHidden(['id','role']);
        if($user->role==1)
        {
            $validation = Validator::make($request->all(), [
                'first_name' => 'required|regex:/^[А-ЯЁа-яё -]+$/u',
                'sure_name' => 'required|regex:/^[А-ЯЁа-яё -]+$/u',
                'othestvo' => 'required|regex:/^[А-ЯЁа-яё -]+$/u',
                'email' => 'required|email|unique:Dostavhuk',
                'phone' => 'required|regex:/^[0-9+]+$/',
                'password' => 'required|regex:/^(?=.*[A-Z])(?=.*[\d])(?=.*[a-z])[a-zA-Z\d]{7,}$/',
                'password_confirmation' => 'same:password',
                'data_rojden' => 'required|date',
                'pol' => 'required|regex:/^[А-ЯЁа-яё -]+$/u',
                'staj_rabotu' => 'required|regex:/^[0-9+]+$/',
                'password_recovery' => 'required'
            ]);
            if ($validation->fails()) {
                $result = ['error' => ['code' => 422, 'message' => 'Validation error', 'error' => $validation->errors()]];
                return response($result, 422);
            }

            $dostavhuk=RegisterDostavhuk::create([
                'first_name' => $request->first_name,
                'sure_name' => $request->sure_name,
                'othestvo' => $request->othestvo,
                'email' => $request->email,
                'phone' => $request->phone,
                'data_rojden' => $request->data_rojden,
                'pol' => $request->pol,
                'staj_rabotu' => $request->staj_rabotu,
                'password_recovery' => $request->password_recovery,
                'password' => Hash::make($request->password),
                'api_token' => Str::random(80)
            ]);
            return response('Успешная регистрация доставщика', 204);
        }
        else
        {
            return response(['data' => ['status' => 'Error', 'Ситуация'=>'Недостаточно прав']], 403);
        }
    }

    public function create41(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|regex:/^[А-ЯЁа-яё -]+$/u',
            'sure_name' => 'required|regex:/^[А-ЯЁа-яё -]+$/u',
            'othestvo' => 'required|regex:/^[А-ЯЁа-яё -]+$/u',
            'email' => 'required|email|unique:Dostavhuk',
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

        return response('Успешная регистрация', 204);

    }
    public function get()
    {
        $RegisterDostavhuk=RegisterDostavhuk::orderBy('id','DESC')->get();
        return response()->json($RegisterDostavhuk);
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

