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

    public function myOrders(){
        $User = Auth::user();
        $myOrders = DB::select("SELECT o.order_number,pg.payment_request_id,pg.payment_processurl,pg.payment_status,p.product_description,p.product_cost FROM orders o INNER JOIN payment_gateway pg ON pg.order_id = o.id INNER JOIN product p ON p.id = o.product_id WHERE o.user_id = $User->id");
        $response = array('error' => false, 'Mensaje'=>$myOrders);
        return response()->json($response,200);
    }

    public function buyProduct(Request $request){
        date_default_timezone_set('America/Bogota');
        $User = Auth::user();
        $seed  = date ('c');
        $seed = strtotime('+5 minute',strtotime($seed));
        $seed = date('c',$seed);
        $order_number = mt_rand();

        if (function_exists('random_bytes')) {
            $nonce = bin2hex(random_bytes(16));
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $nonce = bin2hex(openssl_random_pseudo_bytes(16));
        } else {
            $nonce = mt_rand();
        }      
        
        //Obtener la información del cliente
        $user_info = DB::select("SELECT customer_name,customer_email,customer_mobile FROM users WHERE id = $User->id;");

        //Insertar la información de la orden
        $insert_order = DB::insert("INSERT INTO `orders` (order_number,order_code_product,user_id,product_id,created_at) VALUES (?,?,?,?,NOW())",[$order_number,$request->ProductCode,$User->id,$request->IdProduct]);

        //Obtener id de la orden registrada
        $last_order = collect(DB::select("SELECT MAX(id) as id FROM `orders`"))->first();
        
        $nonceBase64 = base64_encode($nonce);
        $secretKey = env('TRANKEY');
        $tranKey = base64_encode(sha1($nonce . $seed . $secretKey, true));

        $data = array(
            'auth' => [
                'login' => env('LOGIN_PTP'),
                'tranKey' => $tranKey,
                'nonce' => $nonceBase64,
                'seed' => $seed,
            ],
            "buyer" => [
                "name" => "Deion",
                "email" => "dnetix@yopmail.com",
                "mobile" => 3006108300,
            ],
            'payment' => [
                'reference' => $order_number,
                'description' => $request->ProductDescription,
                'amount' => [
                'currency' => 'COP',
                'total' =>   $request->ProductCost,
                ],
            ],
            'expiration' => date('c', strtotime('+1 days')),
            'returnUrl' => 'http://localhost/placetopay_test/public/endProcessPay.php?order="'.$order_number.'"',
            'ipAddress' => '127.0.0.1',
            'userAgent' => 'PlacetoPay Sandbox'            
        );     
        $data = json_encode($data);
        $headers = array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data),
        );
        $curl = curl_init(env('URL_SERVICIO'));
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curl);
        $response = json_decode($response);
        
        $insert_payment = DB::insert("INSERT INTO `payment_gateway` (payment_oder_number,payment_request_id,payment_processurl,payment_status,order_id,created_at) VALUES (?,?,?,?,?,NOW())",[$order_number,$response->requestId,$response->processUrl,'CREATED',$last_order->id]);

        $resp = array('error' => false, 'Mensaje'=>$response);
        return response()->json($resp,200);
    }
}
