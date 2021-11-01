<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Shop;
use Carbon\Carbon;
use Image;


class AuthController extends Controller
{
    public function register(Request $req)
    {
        $file =  $req['shopdata']['fileimg'];
        $req['shop'] = $req['shop'] === 'true'? true : false;

        if($req['shop']){
            $shop = $this->CreateShop($req);
            if(!isset($shop->id)) return $shop;
            $userOrVal = $this->CreateUser($req,$shop);
            if(!isset($userOrVal->id)) return $userOrVal;
            $this->uploadImage($file, $userOrVal);
        }
        else{
            $userOrVal = $this->CreateUser($req);
            if(!isset($userOrVal->id)) return $userOrVal;
        }
        $role = $this->whoIsUser($userOrVal->role);
        return 'Bearer '.$userOrVal->createToken('electron', [$role])->plainTextToken;

    }

    public function Login(Request $res)
    {
        $validator = Validator::make($res->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ],$messages = [
            'required' => ":attribute bo'sh bo'lmasligi kerak.",
            'min' => ":attribute :min simboldan kam bo'lmasligi kerak.",
            'email' => ":attribute to'gri emas",
        ],[
            'email' => "Email",
            'password' => "Parol",
        ]);
        if($validator->fails()) return response()->json($validator->errors(),299);
        if (!$this->guard()->attempt($res->all())) return response()->json([
            ['Email yoki parol xato']
        ], 299);

        $role = $this->whoIsUser($this->guard()->user()->role);
        return 'Bearer '.$this->guard()->user()->createToken('electron', [$role])->plainTextToken;
    }


    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        $this->guard()->logout();
        return response()->json('logout', 200);
    }



    public function guard($guard = 'web')
    {
        return Auth::guard($guard);
    }

    //upload image
    public function uploadImage($image,$user){
        $date = Carbon::now()->format('Ymdhis');

        $nameImg = $user->id.$date;
        $path = public_path('/images/shops');
        $img = Image::make($image->path());
        $img->resize(600, 600, function ($constraint) {
            $constraint->aspectRatio();
        })->save($path.'/'.$nameImg.'.jpg');

        $image->move($path, $nameImg.'main.jpg');
    }


    public function CreateShop($res){
        $validator = Validator::make($res['shopdata'], [
            'name' => 'required|min:3|unique:shops,name',
            'fileimg' => 'required|image|mimes:jpeg,png,jpg|max:35000',
        ],$messages = [
            'required' => ":attribute bo'sh bo'lmasligi kerak.",
            'unique' => ":attribute band.",
            'image' => ":attribute tanlansin.",
            'min' => ":attribute :min simboldan kam bo'lmasligi kerak.",
            'mimes' => ":attribute faqat jpeg, png, jpg formatlarida bo'lsin."
 
        ],[
            'name' => "Do'kon nomi",
            'fileimg' => "Rasm",
        ]);

        if($validator->fails()) return response()->json($validator->errors(),299);
        return Shop::create([
            'name' => $res['shopdata']['name'],
            'image' => '/image',
            'city' => $res['city'],
            'description' =>$res['shopdata']['description'],
            'type' => $res['shopdata']['type'],
        ]);
    }

    public function CreateUser($res,$shop = null){
        if($shop != null){
            $shopId = $shop->id;
        }
        $shopId = $shop != null? $shopId = $shop->id: $shopId = null;

        $validate = Validator::make($res->all(),[
            'name' => 'required|min:3|max:255',
            'city' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|max:255|confirmed',
        ],$messages = [
            'required' => ":attribute bo'sh bo'lmasligi kerak.",
            'unique' => ":attribute band.",
            'min' => ":attribute :min simboldan kam bo'lmasligi kerak.",
            'email' => ":attribute to'gri emas",
            'confirmed' => ":attributelar mos kelmayabdi"
        ],[
            'name' => "F.I.O",
            'email' => "Email",
            'password' => 'Parol'
        ]);

        if($validate->fails()){
            if($shop != null) $shop->delete();
            return response()->json($validate->errors(),299);
        }
        
        return User::create([
            'name' => $res['name'],
            'email' => $res['email'],
            'city' => $res['city'],
            'password' => Hash::make($res['password']),
            'role' => $res['shop'],
            'shop_id' => $shopId,
        ]);
    }

    public function whoIsUser($role){
        $roles = ['client', 'shop','admin'];
        return $roles[$role];
    }
}