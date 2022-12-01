<?php

namespace App\Http\Controllers\Api\V4;

use App\Http\Controllers\Controller;
use App\Models\english_video\Imagees;
use App\Models\V4\Product;
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

class ProductController extends Controller
{

    public function create_product(Request $request)
    {
        $user=Auth::user()->makeHidden(['id','role']);
        if($user->role==1) {
            $error = Validator::make($request->all(), [
                'Category' => 'required|regex:/^[А-ЯЁа-яё -]+$/u',
                'Name_product' => 'required|regex:/^[А-ЯЁа-яё -]+$/u',
                'Weight' => 'required|regex:/^[0-9+]+$/',
                'Price' => 'required|regex:/^[0-9+]+$/',
                'Descriptions' => 'required|regex:/^[А-ЯЁа-яё -]+$/u',
                'Adres_Restoran' => 'required|regex:/^[А-ЯЁа-яё -]+$/u',
                'Name_Restoran' => 'required|regex:/^[А-ЯЁа-яё -]+$/u',
                'image' => 'required|mimes:png',
            ]);//1024
            if ($error->fails()){
                return response([
                    'error' => [
                        'code' => 400,
                        'message' => 'Validation error',
                        'errors' => $error->errors()]], 400);
            }
            else
            {
                $product = new Product;
                $product->Category = $request->Category;
                $product->Name_product = $request->Name_product;
                $product->Weight = $request->Weight;
                $product->Price = $request->Price;
                $product->Descriptions = $request->Descriptions;
                $product->Adres_Restoran = $request->Adres_Restoran;
                $product->Name_Restoran = $request->Name_Restoran;
                $product->image = '/storage/' . Storage::putFile('images', $request->file('image'));
                $product->id_Redaktor = $user->id;
                $product->save();
                return response(['data' => ['status' => 'OK', 'id' => $product->id]], 200);
            }

        }
        else{
            return response(['data' => ['status' => 'Error', 'Ситуация'=>'Недостаточно прав']], 403);
        }
    }
    /* public function create_product_2(Request $request)
     {

         $error=Validator::make($request->all(), [
             'Category' => 'required|regex:/^[А-ЯЁа-яё -]+$/u',
             'Name_product' => 'required|regex:/^[А-ЯЁа-яё -]+$/u',
             'Weight' => 'required|regex:/^[1-9+]+$/',
             'Price' => 'required|regex:/^[1-9+]+$/',
             'Description' => 'required|regex:/^[А-ЯЁа-яё -]+$/u',
             'Adres_Restoran' => 'required|regex:/^[А-ЯЁа-яё -]+$/u',
             'Name_Restoran' => 'required|regex:/^[А-ЯЁа-яё -]+$/u',
             'image'=>'required|mimes:png']);//1024
         if ($error->fails()) return response([
             'error'=>[
                 'code'=>422,
                 'message'=>'Validation error',
                 'errors'=>$error->errors()]], 422);

         $product= Product::create([
             'Category' => $request->Category,
             'Name_product' => $request->Name_product,
             'Weight' => $request->Weight,
             'Price' => $request->Price,
             'Description' => $request->Description,
             'Adres_Restoran' => $request->Adres_Restoran,
             'Name_Restoran' => $request->Name_Restoran,
             'image' => $request->image='/storage/'.Storage::putFile('images', $request->file('image')),
         ]);
         $product->save();
         return response(['data'=>['status'=>'OK', 'id'=>$product->id]], 200);

     }*/

    public function get()
    {
        $Product=Product::orderBy('id','DESC')->get();
        return response()->json($Product);
    }


    public function create(Request $request)
    {
        $images=new Imagees();
        $request->validate([
            'title'=>'required',
            'image'=>'required|max:1024'
        ]);
        $filename="";
        if($request->hasFile('image')){
            $filename=$request->file('image')->store('posts','public');
        }else{
            $filename=Null;
        }

        $images->title=$request->title;
        $images->image=$filename;
        $result=$images->save();
        if($result){
            return response()->json(['success'=>true]);
        }else{
            return response()->json(['success'=>false]);
        }
    }



    public function search_product(Request $request) {
        $product=Product::where('Category', 'like', '%' . $request->Category . '%')->get();
        $result=[];
        foreach ($product as $product){
            $search_product=[
                'Category' => $product->Category,
                'Name_product' => $product->Name_product,
                'Weight' => $product->Weight,
                'Price' => $product->Price,
                'Descriptions' => $product->Description,
                'Adres_Restoran' => $product->Adres_Restoran,
                'Name_Restoran' => $product->Name_Restoran,
                'image' => $product->image
            ];
            array_push($result, $search_product);
        }
        return response(['data'=>['order'=>$result]], 200);
    }



    //Удаление продукта по картинки продукта
    /*public function delete_product2(Request $request)
    {
        $product = Product::where('image', 'like', '%' . $request->image . '%')->first();
        if ($product === null) return response(['error' => ['code' => 404, 'message' => "Order doesn't exist"]], 404);

        $user = Auth::user()->makeHidden(['id', 'role']);
        if ($user->role == 1) {

            @Storage::delete(explode('/storage/', $product->image)[1]);
            $product->delete();
            return response(['data' => ['status' => 'OK']], 200);
        }
        else{
            return response(['error' => ['code' => 403, 'message' => 'Недостаточно прав']], 403);
        }
    }*/




    //Удаление продукта по id
    public function delete_product($id)
    {
        $product = Product::whereId($id)->first();
        $user = Auth::user()->makeHidden(['id', 'role']);
        if ($user->role == 1 )
        {
            if($product!==null)
            {
                @Storage::delete(explode('/storage/', $product->image)[1]);
                $product->delete();
                return response(['data' => ['status' => 'OK']], 200);
            }
            else
            {
                return response(['error'=>['code'=>404, 'message'=>"Order doesn't exist"]], 404);
            }
        }
        else
        {
            return response(['error' => ['code' => 403, 'message' => 'Access denied']], 403);
        }
    }


    //Удаление продукта по id
    public function delete_photo_product(Request $request)
    {
        $product = Product::where('image', 'like', '%' . $request->image . '%')->first();
        $user = Auth::user()->makeHidden(['id', 'role']);
        if ($user->role == 1 )
        {
            if($product!==null)
            {
                @Storage::delete(explode('/storage/', $product->image)[1]);
                $product->delete();
                return response(['data' => ['image' => $product->image]], 200);
            }
            else
            {
                return response(['error'=>['code'=>404, 'message'=>"Order doesn't exist"]], 404);
            }
        }
        else
        {
            return response(['error' => ['code' => 403, 'message' => 'Недостаточно прав']], 403);
        }
    }
}

