@extends('layouts.front')


@section('content')
    <div class="row front">
        
            <div class="col-4">
                @if($store->logo)
                    <img src="{{asset('storage/' . $store->logo)}}" alt="Logo da Loja {{$store->name}}" class="img-fluid" height="200" width="200">
                @else
                    <img src="https://via.placeholder.com/450X100.png?text=logo" alt="Loja sem logo" class="img-fluid">    
                @endif
            </div>

            <div class="col-8">
                <h2>{{$store->name}}</h2>
                <p>{{$store->description}}</p>
                <p>
                    <strong>Contatos:</strong>
                    <span>{{$store->phone}} | {{$store->mobilephone}}</span>
                </p>
                <hr>
            </div>
        
        <div class="col-12">
            <hr>
            <h3 style="margin-bottom: 30px">Produtos da loja</h3>
            
        </div>


        @if ($store->products->count())
            @foreach ($store->products as $key => $product)
            <div class="col-md-4">
                <div class="card" style="width: 98%;">
                    @if($product->photos->count())
                        <img src="{{asset('storage/' . $product->photos->first()->image)}}" alt="" class="card-img-top" style="max-width: 275px" class="img-fluid" height="200" width="200">
                    @else
                        <img src="{{asset('assets/img/no-photo.jpg')}}" alt="" class="card-img-top"> 
                    @endif
                    <div class="card-body">
                        <h2 class="card-title" style="max-width: 275px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis">{{$product->name}}</h2>
                        <p class="card-text" style="max-width: 275px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis">
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
                            <button class="btn btn-primary">Comprar</button>
                        </form>
                        
                        <!-- <a href="{{route('product.single', ['slug' => $product->slug])}}" class="btn btn-success">Ver Produto</a>
                        -->
                    </div>
                </div>     
            </div>
            @if(($key + 1) % 3 == 0) </div><div class="row front"> @endif
        @endforeach

        @else
                <h3 class="alert alert-warning"> Não existem produtos para esta loja!</h3>

        @endif

    </div>
@endsection