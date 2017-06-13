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


/*
  |--------------------------------------------------------------------------
  | Mage2 Tax Class Module Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an mage2 tax class modules.
  | It's a breeze. Simply tell mage2 tax class module the URI it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */
Route::group(['middleware' => ['web', 'adminauth'], 'namespace' => "Mage2\TaxClass\Controllers\Admin"], function () {

    Route::resource('/admin/tax-rule', 'TaxRuleController', ['as' => 'admin']);

    Route::get('/admin/configuration/tax-class', ['as' => 'admin.configuration.tax-class', 'uses' => 'ConfigurationController@getConfiguration']);
});


Route::group(['middleware' => ['web'], 'namespace' => "Mage2\TaxClass\Controllers"], function () {

    Route::post('/tax-calculation', ['as' => 'tax.calculation', 'uses' => 'TaxRuleController@getTaxAmount']);
});