<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shoptype;
use App\Models\Shop;
use URL;
class ShopController extends Controller
{
    public function index(Request $req){
        return response()->json($req->user(), 200);
    }

    public function all(){
        $shops = Shop::all();

        foreach ($shops as $i => $shop) {
            $shops[$i]['shoptype'] = Shoptype::find($shop->shoptype_id)->name;
        }

        return $shops;
    }

    public function shop($id){
        return Shop::find($id);
    }

    public function cafeTypes(){
        $types = Shoptype::all();
        $url = URL::to('/');
        foreach ($types as $key => $item) {
            $types[$key]->image = $url.$item->image;
        }
        return $types;
    }

    public function shopInType($type_id){
        return Shop::where('shoptype_id' , $type_id)->get();
    }
}
