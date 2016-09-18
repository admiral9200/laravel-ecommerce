<div class="card card-primary">
    <div class="card-content">

        <a href="{{ route('product.view', $product->slug)}}" title="{{ $product->title }}">
            @if(isset($product->getProductImages($first = true)->value))
                <img alt="{{ $product->title }}"
                     class="responsive-img"
                     src="{{ asset('/uploads/catalog/images/'. $product->getProductImages($first= true)->value) }}" />
            @else
                <img alt="{{ $product->title }}"
                     class="responsive-img"
                     src="{{ asset('/img/default-product.jpg') }}" />
            @endif
        </a>
        <div class="caption">
            <h3>
                <a href="{{ route('product.view', $product->slug)}}" title="{{ $product->title }}">
                    {{ $product->title }}
                </a>
            </h3>
            <p class="price">
                $ {{ $product->price }}
            </p>
            <p>
                <a class="btn btn-primary" href="{{ route('cart.add-to-cart', $product->id) }}">Add to Cart</a>
                @if(isset(Auth::user()->id) && Auth::user()->isInWishlist($product->id))
                    <a class="btn btn-danger" href="{{ route('wishlist.remove', $product->id) }}">Remove from Wishlist</a>
                @else
                    <a class="btn btn-warning" href="{{ route('wishlist.add', $product->id) }}">Add to Wishlist</a>
                @endif
            </p>
        </div>
    </div>
</div>