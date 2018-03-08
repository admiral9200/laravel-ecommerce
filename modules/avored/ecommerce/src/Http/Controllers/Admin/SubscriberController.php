<?php
namespace AvoRed\Ecommerce\Http\Controllers\Admin;

use AvoRed\Ecommerce\Models\Database\Subscriber;
use AvoRed\Ecommerce\Http\Requests\Admin\SubscriberRequest;
use AvoRed\Ecommerce\DataGrid\Facade as DataGrid;

class SubscriberController extends AdminController
{

    /**
     * Display a listing of the Subscriber.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataGrid = DataGrid::model(Subscriber::query()->orderBy('id','desc'))
            ->column('id',['sortable' => true])
            ->column('name')
            ->column('email')
            ->linkColumn('edit',[], function($model) {
                return "<a href='". route('admin.subscriber.edit', $model->id)."' >Edit</a>";

            })->linkColumn('destroy',[], function($model) {
                return "<form id='admin-subscriber-destroy-".$model->id."'
                                            method='POST'
                                            action='".route('admin.subscriber.destroy', $model->id) ."'>
                                        <input name='_method' type='hidden' value='DELETE' />
                                        ". csrf_field()."
                                        <a href='#'
                                            onclick=\"jQuery('#admin-subscriber-destroy-$model->id').submit()\"
                                            >Destroy</a>
                                    </form>";
            });

        return view('avored-ecommerce::admin.subscriber.index')->with('dataGrid', $dataGrid);
    }

    /**
     * Show the form for creating a new page.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('avored-ecommerce::admin.subscriber.create');
    }

    /**
     * Store a newly created subscriber in database.
     *
     * @param \AvoRed\Ecommerce\Http\Requests\Admin\SubscriberRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(SubscriberRequest $request)
    {
        Subscriber::create($request->all());

        return redirect()->route('admin.subscriber.index');
    }

    /**
     * Show the form for editing the specified subscriber.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $subscriber = Subscriber::findorfail($id);

        return view('avored-ecommerce::admin.subscriber.edit')->with('model', $subscriber);
    }

    /**
     * Update the specified subscriber in database.
     *
     * @param \AvoRed\Ecommerce\Http\Requests\Admin\Subscriber $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(SubscriberRequest $request, $id)
    {
        $subscriber = Subscriber::findorfail($id);
        $subscriber->update($request->all());

        return redirect()->route('admin.subscriber.index');
    }

    /**
     * Remove the specified subscriber from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Subscriber::destroy($id);

        return redirect()->route('admin.subscriber.index');
    }
}
