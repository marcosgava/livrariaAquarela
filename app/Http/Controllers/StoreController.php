<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Store;
use App\UserOrder;

class StoreController extends Controller
{
    private $store;

    public function __construct(Store $store)
    {
        $this->store = $store;    
    }

    public function index($slug) {
        $categories = \App\Category::all(['name', 'slug']);
        $store = $this->store->whereSlug($slug)->first();
        

        return view('store', compact('store', 'categories'));
    }
}
