<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class OrderController extends Controller
{
    public function index(){
        return view('order');
    }

    public function searchProducts(){
        $products = DB::select("SELECT * FROM product");
        $response = array('error' => false, 'Mensaje'=>$products);
        return response()->json($response,200);
    }

    public function buyProduct(Request $request){
        $User = Auth::user();
        $request->IdProduct;
        $request->ProductCode;
        $request->ProductCost;
        $User->id;
    }
}
