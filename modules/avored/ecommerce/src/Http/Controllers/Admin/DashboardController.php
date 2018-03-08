<?php
namespace AvoRed\Ecommerce\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use AvoRed\Ecommerce\Models\Database\Configuration;
use AvoRed\Ecommerce\Models\Database\Order;
use AvoRed\Ecommerce\Models\Database\Visitor;

class DashboardController extends AdminController
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $visitorLabelCollection = Collection::make([]);
        $visitorValueCollection = Collection::make([]);
        $todaysDateCarbon = Carbon::today();

        for($i = 0; $i<=6; $i++) {

            $visitorCountForThatDay = Visitor::whereDay('created_at','=', $todaysDateCarbon->day)->get()->count();
            $visitorLabelCollection->push('"' .$todaysDateCarbon->format('d-M-y'). '"' );
            $visitorValueCollection->push($visitorCountForThatDay);

            $todaysDateCarbon->subDay(1);

        }

        $value = Configuration::getConfiguration('avored_user_total');
        $totalRegisteredUser = (null === $value) ? 0 : $value;
        $totalOrder = Order::all()->count();

        return view('avored-ecommerce::admin.home')
            ->with('totalRegisteredUser', $totalRegisteredUser)
            ->with('visitorLabelCollection', implode(",", $visitorLabelCollection->all()))
            ->with('visitorValueCollection', implode(",", $visitorValueCollection->all()))
            ->with('totalOrder', $totalOrder)
            ;
    }
}
