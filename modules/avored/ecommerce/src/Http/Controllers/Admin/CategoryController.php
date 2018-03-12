<?php
namespace AvoRed\Ecommerce\Http\Controllers\Admin;

use AvoRed\Ecommerce\Http\Requests\CategoryRequest;
use AvoRed\Framework\DataGrid\Facade as DataGrid;
use AvoRed\Framework\Repository\Category as CategoryRepository;

class CategoryController extends AdminController
{

    public $categoryRepository;

    public function __construct(CategoryRepository $respository)
    {
        $this->categoryRepository = $respository;
    }

    /**
     * Display a listing of the Category.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categoryModel = $this->categoryRepository->model();

        $dataGrid = DataGrid::model($categoryModel->query())
                        ->column('name',['label' => 'Name','sortable' => true])
                        ->column('slug',['sortable' => true])
                        ->linkColumn('edit',[], function($model) {
                            return "<a href='". route('admin.category.edit', $model->id)."' >Edit</a>";

                        })->linkColumn('destroy',[], function($model) {
                            return "<form id='admin-category-destroy-".$model->id."'
                                            method='POST'
                                            action='".route('admin.category.destroy', $model->id) ."'>
                                        <input name='_method' type='hidden' value='DELETE' />
                                        ". csrf_field()."
                                        <a href='#'
                                            onclick=\"jQuery('#admin-category-destroy-$model->id').submit()\"
                                            >Destroy</a>
                                    </form>";
                        });

        return view('avored-ecommerce::admin.category.index')->with('dataGrid', $dataGrid);
    }

    /**
     * Show the form for creating a new Category.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('avored-ecommerce::admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \AvoRed\Ecommerce\Http\Requests\CategoryRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {

        $this->categoryRepository->create($request->all());

        return redirect()->route('admin.category.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = $this->categoryRepository->find($id);

        return view('avored-ecommerce::admin.category.edit')->with('model', $category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \AvoRed\Ecommerce\Http\Requests\CategoryRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $id)
    {
        $category = $this->categoryRepository->find($id);
        $category->update($request->all());

        return redirect()->route('admin.category.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = $this->categoryRepository->find($id);

        foreach ($category->children as $child) {
            $child->parent_id = 0;
            $child->update();
        }

        $category->delete();

        return redirect()->route('admin.category.index');
    }
}
