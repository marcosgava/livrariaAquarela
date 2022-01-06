<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Store;
use App\UserOrder;
use App\User;

class UserOrderController extends Controller
{
    public function index(){
        
        $userOrders = auth()->user()->orders()->paginate(5);


        return view('user-orders', compact('userOrders'));
    }
}
