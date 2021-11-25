@extends('layouts.front')


@section('content')
    <div class="row front">
        @foreach ($products as $key => $product)
            <div class="col-md-4">
                <div class="card" style="width: 98%;">
                    @if($product->photos->count())
                        <img src="{{asset('storage/' . $product->photos->first()->image)}}" alt="" class="card-img-top" class="img-fluid" height="200" width="200">
                    @else
                        <img src="{{asset('assets/img/no-photo.jpg')}}" alt="" class="card-img-top" class="img-fluid" height="200" width="200"> 
                    @endif
                    <div class="card-body">
                        <h2 class="card-title" style="max-width: 275px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis" title="{{$product->name}}">{{$product->name}}</h2>
                        <p class="card-text" style="max-width: 275px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis" title="{{$product->description}}">
                            {{$product->description}}
                        </p>
                        <h3>
                            R$ {{number_format($product->price, '2', ',', '.')}}
                        </h3>
                        
                        <form action="{{route('cart.add')}}" method="post">
                            @csrf
                            <input type="hidden" name="product[name]" value="{{$product->name}}">
                            <input type="hidden" name="product[price]" value="{{$product->price}}">
                            <input type="hidden" name="product[slug]" value="{{$product->slug}}">
                            <div class="form-group">
                                <label for="">Quantidade</label>
                                <input type="number" name="product[amount]" class="form-control col-md-2" value="1">
                            </div>
                            <a href="{{route('product.single', ['slug' => $product->slug])}}" class="btn btn-success">Ver Produto</a>
                            <button class="btn btn-danger">Comprar</button>
                        </form>
                    </div>
                </div>     
            </div>
            @if(($key + 1) % 3 == 0) </div><div class="row front"> @endif
                
        @endforeach
        
    </div>

    <div class="row">
        <div class="col-12">
            <h2>Lojas Destaque</h2>
            <hr>
        </div>
        @foreach($stores as $store)
            <div class="col-4">
                @if($store->logo)
                    <img src="{{asset('storage/' . $store->logo)}}" alt="Logo da Loja {{$store->name}}" class="img-fluid" height="200" width="200">
                @else
                    <img src="https://via.placeholder.com/450X100.png?text=logo" alt="Loja sem logo" class="img-fluid">    
                @endif
                
                <h3>{{$store->name}}</h3>
                <p>
                    {{$store->description}}
                </p>
                
                <a href="{{route('store.single', ['slug' => $store->slug])}}" class="btn btn-sm btn-success" style="margin-bottom: 20px">Ver Loja</a>
            </div>
        @endforeach

    </div>
@endsection