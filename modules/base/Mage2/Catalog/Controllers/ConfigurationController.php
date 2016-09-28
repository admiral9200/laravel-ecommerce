<?php

namespace Mage2\Catalog\Controllers;

use Illuminate\Support\Collection;

use Mage2\Catalog\Models\Category;
use Mage2\Catalog\Requests\CategoryRequest;
use Mage2\Framework\Http\Controllers\Controller;

class ConfigurationController extends Controller
{
  
    public function __construct(){
      
        parent::__construct();
    }
    /**
     * Display a listing of the Catalog Configuration
     * @return \Illuminate\Http\Response
     */
    public function getConfiguration()
    {
        //$categories = Category::orderBy('id','desc')->paginate(10);

        return view('admin.configuration.index')
            ;
    }

    /**
     * Stora the Catalog Configuration.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $categoryOptions = $this->_getCategoryOptions();
        return view('category.create')
                ->with('categoryOptions', $categoryOptions)
            ;
    }
}
