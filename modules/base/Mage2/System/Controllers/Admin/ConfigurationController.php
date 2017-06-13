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


namespace Mage2\System\Controllers\Admin;

use Illuminate\Http\Request;
use Mage2\System\Models\Configuration;
use Mage2\Framework\System\Controllers\AdminController;
use Mage2\Framework\Configuration\Facades\AdminConfiguration;
use Mage2\Page\Models\Page;
use Illuminate\Support\Collection;

class ConfigurationController extends AdminController
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $configurations = AdminConfiguration::all();

        return view('mage2system::admin.configuration.index')
            ->with('configurations', $configurations);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        foreach ($request->except(['_token', '_method']) as $key => $value) {
            $configuration = Configuration::where('configuration_key', '=', $key)->get()->first();

            if (null === $configuration) {
                $data['configuration_key'] = $key;
                $data['configuration_value'] = $value;


                Configuration::create($data);


            } else {
                $configuration->update(['configuration_value' => $value]);
            }
        }


        return redirect()->back()->with('notificationText', 'All Configuration saved.');
    }

    /**
     * Display a System General Configuration.
     *
     * @return \Illuminate\Http\Response
     */
    public function getGeneralConfiguration()
    {
        $configurations = Configuration::all()->pluck('configuration_value', 'configuration_key');
        $pageOptions = Collection::make(['' => 'Please Select'] + Page::all()->pluck('title', 'id')->toArray());


        return view('mage2system::admin.configuration.general-configuration')
            ->with('configurations', $configurations)
            ->with('pageOptions', $pageOptions);
    }
}
