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
namespace Mage2\Ecommerce\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use Mage2\Ecommerce\Models\Database\Country;
use Mage2\Ecommerce\Models\Database\TaxRule;
use Mage2\Ecommerce\Http\Requests\TaxRuleRequest;
use Mage2\Ecommerce\DataGrid\Facade as DataGrid;

class TaxRuleController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataGrid = DataGrid::model(TaxRule::query()->orderBy('id','desc'))
            ->column('id',['sortable' => true])
            ->column('name')
            ->linkColumn('edit',[], function($model) {
                return "<a href='". route('admin.tax-rule.edit', $model->id)."' >Edit</a>";

            })->linkColumn('destroy',[], function($model) {
                return "<form id='admin-tax-rule-destroy-".$model->id."'
                                            method='POST'
                                            action='".route('admin.tax-rule.destroy', $model->id) ."'>
                                        <input name='_method' type='hidden' value='DELETE' />
                                        ". csrf_field()."
                                        <a href='#'
                                            onclick=\"jQuery('#admin-tax-rule-destroy-$model->id').submit()\"
                                            >Destroy</a>
                                    </form>";
            });

        return view('mage2-ecommerce::admin.tax-rule.index')->with('dataGrid', $dataGrid);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countryOptions = [null => 'Please Select'] + Country::all()->pluck('name', 'id')->toArray();
        return view('mage2-ecommerce::admin.tax-rule.create')->with('countryOptions', $countryOptions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Mage2\Ecommerce\Http\Requests\TaxRuleRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(TaxRuleRequest $request)
    {
        TaxRule::create($request->all());

        return redirect()->route('admin.tax-rule.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $countryOptions = [null => 'Please Select'] + Country::all()->pluck('name', 'id')->toArray();
        $taxRule = TaxRule::findorfail($id);

        return view('mage2-ecommerce::tax-rule.edit')
            ->with('model', $taxRule)
            ->with('countryOptions', $countryOptions);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Mage2\Ecommerce\Http\Requests\TaxRuleRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(TaxRuleRequest $request, $id)
    {
        $taxRule = TaxRule::findorfail($id);
        $taxRule->update($request->all());

        return redirect()->route('admin.tax-rule.index');
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
        TaxRule::destroy($id);
        return redirect()->route('admin.tax-rule.index');
    }
}