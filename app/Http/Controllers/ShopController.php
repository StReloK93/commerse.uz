<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use URL;
use DB;
class ShopController extends Controller
{
    public function index(Request $req){
        return response()->json($req->user(), 200);
    }


    public function cafeTypes(){

        $types = DB::table('shoptype')->get();
        $url = URL::to('/');
        foreach ($types as $key => $item) {
            $types[$key]->image =$url.$item->image;
        }
        return $types;


    }
}
