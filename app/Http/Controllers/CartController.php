<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Store;
use App\Payment\PagSeguro\CreditCard;
use App\Payment\PagSeguro\Notification;


class CartController extends Controller
{
    public function index() {
        //$categories = \App\Category::all(['name', 'slug']);
        $cart = session()->has('cart') ? session()->get('cart') : [];
        return view('cart', compact('cart'));
    }
    
    public function add(Request $request){
        $productData = $request->get('product');

        $product = \App\Product::whereSlug($productData['slug']);

        if(!$product->count() || $productData['amount'] <= 0) 
            return redirect()->route('home');

        $product = array_merge($productData,
                               $product->first(['id', 'name', 'price', 'store_id'])->toArray());

        //Verificar existência de sessão para os Produtos
        if(session()->has('cart')) {
            $products = session()->get('cart');
            $productsSlugs =  array_column($products, 'slug');

            if(in_array($product['slug'], $productsSlugs)){
                $products = $this->productIncrement($product['slug'], $product['amount'], $products);

                session()->put('cart', $products);
            } else{
                //Se existir add o produto na sessão existante
                session()->push('cart', $product);
            }
        } else{
          //Não existindo criar sessão com o primeiro produto add
            $products[] = $product;
            session()->put('cart', $products);

        }

        flash('Produto adicionado ao carrinho')->success();
        return redirect()->route('product.single', ['slug' => $product['slug']]);
  
    }

    public function remove($slug){
        if(!session()->has('cart'))
            return redirect()->route('cart.index');

        $products = session()->get('cart');
        $products = array_filter($products, function($line) use($slug){
            return $line['slug'] != $slug;
        });

        session()->put('cart', $products);
        return redirect()->route('cart.index');
    }

    public function cancel() {
        session()->forget('cart');
        
        flash('Compra Cancelada pelo usuário com sucesso')->success();
        return redirect()->route('cart.index');
    }

    private function productIncrement($slug, $amount, $products){

        $products = array_map(function($line) use($slug, $amount) {
            if($slug == $line['slug']) {
                $line['amount'] += $amount;
            }
            return $line;
        }, $products);

        return $products;
    }

}
