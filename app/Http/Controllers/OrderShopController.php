<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderShopController extends Controller
{
    public function index(){
        return view('ordershop');
    }

    public function orders(){
        $myOrders = DB::select("SELECT o.order_number,pg.payment_request_id,pg.payment_processurl,pg.payment_status,p.product_description,p.product_cost FROM orders o INNER JOIN payment_gateway pg ON pg.order_id = o.id INNER JOIN product p ON p.id = o.product_id ORDER BY pg.created_at DESC");
        $response = array('error' => false, 'Mensaje'=>$myOrders);
        return response()->json($response,200);
    }
}
