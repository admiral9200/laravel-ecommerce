<?php

namespace App\Http\Controllers;

use AvoRed\Framework\Models\Database\Product;
use AvoRed\Framework\Models\Contracts\ProductInterface;

class ProductViewController extends Controller
{
    /**
     * Product Repository
     * @var \AvoRed\Framework\Models\Repository\ProductRepository
     */
    protected $repository;

    public function __construct(ProductInterface $repository)
    {
        $this->repository = $repository;
    }

    public function view($slug)
    {
        $product = $this->repository->findBySlug($slug);
        $title = (!empty($product->meta_title)) ?
                                            $product->meta_title :
                                            $product->name;

        $description = (!empty($product->meta_description)) ?
                                        $product->meta_description :
                                        substr($product->description, 0, 255);

        return view('product.view')
                                ->with('product', $product)
                                ->with('title', $title)
                                ->with('description', $description);
    }
}
