<?php

if(!isset( $_POST['order'])){
    $order = $_POST['order'];
   // consultar el request id
   /**SELECT pg.payment_request_id FROM
        orders o
        INNER JOIN payment_gateway pg
        ON o.order_number = pg.payment_oder_number
        WHERE o.order_number = $order 
        
        $request_id = payment_request_id
        */

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

    $data = array(
        'auth' => [
            'login' => env('LOGIN_PTP'),
            'tranKey' => $tranKey,
            'nonce' => $nonceBase64,
            'seed' => $seed,
        ],
    );     
    $data = json_encode($data);
    $headers = array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data),
    );
    $url = env('URL_SERVICIO');
    $url = $url."/".$request_id;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($curl);
    $response = json_decode($response);

}

?>