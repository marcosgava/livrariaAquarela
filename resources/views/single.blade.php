@extends('layouts.front')


@section('content')
    <div class="row">
        <div class="col-6">
            @if($product->photos->count())
            <img src="{{asset('storage/' . $product->thumb)}}" alt="" class="card-img-top thumb">
                {{--<img src="{{asset('storage/' . $product->photos->first()->image)}}" alt="" class="card-img-top">--}}
                <div class="row" style="margin-top: 20px;">
                    @foreach($product->photos as $photo)
                        <div class="col-4">
                            <img src="{{asset('storage/' . $photo->image)}}" alt="" class="img-fluid">
                        </div>
                    @endforeach 
                </div>
                    
            @else
                <img src="{{asset('assets/img/no-photo.jpg/')}}" alt="" class="card-img-top"> 
            @endif
        </div>

        <div class="col-6">
            <div class="col-md-12">
                <h2>{{$product->name}}</h2>
            <p>
                {{$product->description}}
            </p>
            <h3>
                R$ {{number_format($product->price, '2', ',', '.')}}
            </h3>
        </div>

            <div class="product-add col-md-12">
                <hr>
                <form action="{{route('cart.add')}}" method="post">
                    @csrf
                    <input type="hidden" name="product[name]" value="{{$product->name}}">
                    <input type="hidden" name="product[price]" value="{{$product->price}}">
                    <input type="hidden" name="product[slug]" value="{{$product->slug}}">
                    <div class="form-group">
                        <label style="font-size: 18px">Quantidade</label>
                        <input type="number" name="product[amount]" class="form-control col-md-2" value="1">
                    </div>
                    <button class="btn btn-lg btn-primary">Comprar</button>
                    <a href="{{route('home')}}" class="btn btn-lg btn-secondary">Voltar</a>
                    
                </form>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-12" style="font-size: 28px">
            <hr>
            {{$product->body}}
        </div>
    </div>
    <br>
    <br>
@endsection