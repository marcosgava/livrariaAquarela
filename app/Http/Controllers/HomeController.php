<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Product;

class HomeController extends Controller
{
    private $product;

    public function __construct(Product $product){
        $this->product = $product;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $products = \App\Store::paginate(9);
        $products = $this->product->limit(9)->get();
        $stores = \App\Store::limit(3)->get();
       // $categories = \App\Category::all(['name', 'slug']);

        return view('welcome', compact('products', 'stores'));
    }

    public function single($slug) {
        $product = $this->product->whereSlug($slug)->first();
        

        return view('single', compact('product'));
    }
    
}
