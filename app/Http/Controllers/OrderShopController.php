<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class OrderShopController extends Controller
{
    public function index(){
        return view('ordershop');
    }
}
