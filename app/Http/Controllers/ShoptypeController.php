<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class ShoptypeController extends Controller
{
    public function all(){
        return DB::table('shoptype')->get();
    }
}
