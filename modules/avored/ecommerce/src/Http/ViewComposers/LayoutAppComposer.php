<?php
namespace AvoRed\Ecommerce\Http\ViewComposers;

use Illuminate\Support\Facades\Session;
use AvoRed\Framework\Repository\Category;
use Illuminate\View\View;
use AvoRed\Ecommerce\Models\Database\Configuration;

class LayoutAppComposer
{

    /*
    * AvoRed Framework Category Repository
    *
    * @var \AvoRed\Framework\Repository\Category
    */
    public $categoryRepository;

    public function __construct(Category $repository)
    {
        $this->categoryRepository=  $repository;

    }



    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        $cart = (null === Session::get('cart')) ? 0 : count(Session::get('cart'));
        $categoryModel = $this->categoryRepository->model();
        $baseCategories = $categoryModel->getAllCategories();

        $metaTitle = Configuration::getConfiguration('general_site_title');
        $metaDescription = Configuration::getConfiguration('general_site_description');


        $view->with('categories', $baseCategories)
            ->with('cart', $cart)
            ->with('metaTitle', $metaTitle)
            ->with('metaDescription', $metaDescription);
    }

}
