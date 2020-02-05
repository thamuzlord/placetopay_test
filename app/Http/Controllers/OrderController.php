<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Dnetix\Redirection\PlacetoPay;
use GuzzleHttp\Client;


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

        $datos = array(
            'auth' => [
                'login' => env('LOGIN_PTP'),
                'tranKey' => $tranKey,
                'nonce' => $nonceBase64,
                'seed' => $seed,
            ],
            'payment' => [
                'reference' => $request->ProductCode,
                'description' => 'Testing payment',
                'amount' => [
                'currency' => 'COP',
                'total' =>   $request->ProductCost,
                ],
            ],
            'expiration' => date('c', strtotime('+2 days')),
            'returnUrl' => 'http://localhost/placetopay_Santiago/public/order',
            'ipAddress' => '127.0.0.1',
            'userAgent' => 'PlacetoPay Sandbox'            
        );     
        $_data = json_encode($datos);
        $url = 'https://dev.placetopay.com/redirection/api/session';
        $headers = array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($_data),
        );
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $_data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curl);
        var_dump($response);


        // Nuevo cliente con un url base
        /*$client = new GuzzleHttp\Client(['base_uri' => 'https://dev.placetopay.com/redirection/']);
        $response = $client->post("https://dev.placetopay.com/redirection/api/session", [
            'auth' => [
                'login' => env('LOGIN_PTP'),
                'tranKey' => $tranKey,
                'nonce' => $nonceBase64,
                'seed' => $seed,
            ],
        ]);

        var_dump($response);*/


       /* $placetopay = new PlacetoPay([
            'login' => env('LOGIN_PTP'),
            'tranKey' => $tranKey,
            'nonce' => $nonceBase64,
            'seed' => $seed,
            'url' => env('URL_SERVICIO'),
        ]);

        $requestOrder = [
        'payment' => [
            'reference' => $request->ProductCode,
            'description' => 'Testing payment',
            'amount' => [
            'currency' => 'COP',
            'total' =>   $request->ProductCost,
            ],
        ],
        'expiration' => date('c', strtotime('+2 days')),
        'returnUrl' => 'http://localhost/placetopay_Santiago/public/order',
        'ipAddress' => '127.0.0.1',
        'userAgent' => 'PlacetoPay Sandbox',
        ];

        $response = $placetopay->request($requestOrder);*/
        // if ($response->isSuccessful()) {
        //     // STORE THE $response->requestId() and $response->processUrl() on your DB associated with the payment order
        //     // Redirect the client to the processUrl or display it on the JS extension
        //   //  header('Location: ' . $response->processUrl());
        // } else {
        //     // There was some error so check the message and log it
        //     $response->status()->message();
        // }

        //var_dump($response);

        }
}
