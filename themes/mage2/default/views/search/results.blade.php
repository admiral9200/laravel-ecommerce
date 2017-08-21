@extends('layouts.app')

@section('meta_title')
    {{ "Search results for " . $queryParam }}
@endsection

@section('content')
    <div class="row">
        <h2> Search Results for: {{ $queryParam }}</h2>

        <div class="col-12">
            @if(count($products) <= 0)
                <p>Sorry No Product Found</p>
            @else
                <div class="row">
                @foreach($products as $product)
                        <div class="col-4">
                            @include('catalog.product.view.product-card',['product' => $product])
                    </div>

                @endforeach
                {!!  $products->links() !!}
                </div>
            @endif
        </div>
    </div>
@endsection
