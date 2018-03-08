<?php
namespace Mage2\Ecommerce\Http\Controllers\Admin;

use Mage2\Ecommerce\Models\Database\Attribute;
use Mage2\Ecommerce\DataGrid\Facade as DataGrid;
use Mage2\Ecommerce\Http\Requests\AttributeRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class AttributeController extends AdminController
{
    public function index()
    {
        $dataGrid = DataGrid::model(Attribute::query())
            ->column('name',['label' => 'Name','sortable' => true])
            ->column('identifier',['sortable' => true])
            ->linkColumn('edit',[], function($model) {
                return "<a href='". route('admin.attribute.edit', $model->id)."' >Edit</a>";

            })->linkColumn('destroy',[], function($model) {
                return "<form id='admin-attribute-destroy-".$model->id."'
                                            method='POST'
                                            action='".route('admin.attribute.destroy', $model->id) ."'>
                                        <input name='_method' type='hidden' value='DELETE' />
                                        ". csrf_field()."
                                        <a href='#'
                                            onclick=\"jQuery('#admin-attribute-destroy-$model->id').submit()\"
                                            >Destroy</a>
                                    </form>";
            });

//        return view('mage2-framework::category.index')->with('dataGrid', $dataGrid);


        return view('mage2-ecommerce::admin.attribute.index')->with('dataGrid', $dataGrid);
    }

    public function create()
    {

        return view('mage2-ecommerce::admin.attribute.create');
    }

    public function store(AttributeRequest $request)
    {

        $attribute = Attribute::create($request->all());
        $this->_saveDropdownOptions($attribute , $request);

        return redirect()->route('admin.attribute.index');


    }

    public function edit($id)
    {
        $attribute = Attribute::find($id);
        return view('mage2-ecommerce::admin.attribute.edit')->with('model', $attribute);

    }

    public function update(AttributeRequest $request, $id)
    {

        $attribute = Attribute::find($id);
        $attribute->update($request->all());
        $this->_saveDropdownOptions($attribute , $request);

        return redirect()->route('admin.attribute.index');

    }

    /**
     *
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {

        Attribute::destroy($id);

        return redirect()->route('admin.attribute.index');
    }


    public function getAttribute(Request $request)
    {
        $attribute = Attribute::findorfail($request->get('id'));

        return view('mage2-ecommerce::admin.attribute.attribute-card-values')
            ->with('attribute', $attribute);

    }


    /**
     * Get the Element Html in Json Response.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function getElementHtml(Request $request)
    {
        $attributes = Attribute::whereIn('id',$request->get('attribute_id'))->get();

        //foreach ($attributes as $)
        $tmpString = "__RANDOM__STRING__";
        $view = view('mage2-ecommerce::admin.attribute.get-element')
            ->with('attributes', $attributes)
            ->with('tmpString', $tmpString);


        return new JsonResponse(['success' => true,'content' => $view->render()]);
    }



    private function _saveDropdownOptions($attribute, $request)
    {

        if (null !== $request->get('dropdown-options')) {

            if(null != $attribute->attributeDropdownOptions()->get() && $attribute->attributeDropdownOptions()->get()->count() >= 0) {

                $attribute->attributeDropdownOptions()->delete();
            }
            foreach ($request->get('dropdown-options') as $key => $val) {
                if ($key == '__RANDOM_STRING__') {
                    continue;
                }

                $attribute->attributeDropdownOptions()->create($val);

            }
        }
    }

}
