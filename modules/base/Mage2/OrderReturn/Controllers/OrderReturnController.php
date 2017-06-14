<?php
/**
 * Mage2
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to ind.purvesh@gmail.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://mage2.website for more information.
 *
 * @author    Purvesh <ind.purvesh@gmail.com>
 * @copyright 2016-2017 Mage2
 * @license   https://www.gnu.org/licenses/gpl-3.0.en.html GNU General Public License v3.0
 */
namespace Mage2\OrderReturn\Controllers;

use Illuminate\Support\Facades\Auth;
use Mage2\Order\Models\Order;
use Mage2\Framework\System\Controllers\Controller;
use Mage2\OrderReturn\Requests\OrderReturnRequest;
use Mage2\OrderReturn\Models\OrderReturnRequest as OrderReturnRequestModel;
use Mage2\OrderReturn\Models\OrderReturnRequestProduct;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Mage2\OrderReturn\Models\OrderReturnRequestMessage;

class OrderReturnController extends Controller
{
    /**
     *
     * Create Product Return Request
     *
     * @param int $id
     */
    public function create($id) {

        $order = Order::find($id);

        return view('mage2orderreturn::my-account.order-return.create')
                        ->with('order', $order);
    }

    public function store(OrderReturnRequest $request, $id) {

        $user = Auth::user();
        $request->merge(['order_id' => $id]);
        $orderReturnRequest = OrderReturnRequestModel::create($request->all());

        OrderReturnRequestMessage::create(['order_return_request_id' => $orderReturnRequest->id,
                                            'message_text' => $request->get('message'),
                                            'user_id' => $user->id,
                                            'user_type' => 'USER'
                                            ]);

        $products = $request->get('products');

        foreach($products as $productId => $qty) {
            OrderReturnRequestProduct::create(['order_return_request_id' => $orderReturnRequest->id,'product_id' => $productId,'qty' => $qty]);
        }

        return redirect()->route('my-account.home');
    }

}
