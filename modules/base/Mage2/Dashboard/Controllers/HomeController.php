<?php

namespace Mage2\Dashboard\Controllers;

use Mage2\Catalog\Models\Product;
use Mage2\System\Controllers\Controller;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = new Product();
        $featureProducts = $product->getFeaturedProducts();

        return view('home.index')
            ->with('featuredProducts', $featureProducts);
    }
}
