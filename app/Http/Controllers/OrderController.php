<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Dnetix\Redirection\PlacetoPay;


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
        date_default_timezone_set('America/Bogota');
        $User = Auth::user();
        $request->IdProduct;
        $request->ProductCode;
        $request->ProductCost;
        $User->id;

        $seed  = date ('c');
        $seed = strtotime('+5 minute',strtotime($seed));
        $seed = date('c',$seed);

        if (function_exists('random_bytes')) {
            $nonce = bin2hex(random_bytes(16));
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $nonce = bin2hex(openssl_random_pseudo_bytes(16));
        } else {
            $nonce = mt_rand();
        }
        
        $nonceBase64 = base64_encode($nonce);
        $secretKey = env('TRANKEY');
        $tranKey = base64_encode(sha1($nonce . $seed . $secretKey, true));

        $placetopay = new PlacetoPay([
            'login' => 'YOUR_LOGIN',
            'tranKey' => $tranKey,
            'url' => 'https://THE_BASE_URL_TO_POINT_AT',
        ]);

    }
}
