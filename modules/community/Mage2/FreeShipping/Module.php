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

namespace Mage2\FreeShipping;

use Illuminate\Support\Facades\View;
use Mage2\Framework\Shipping\Facades\Shipping;
use Mage2\FreeShipping\Shipping\FreeShipping;
use Mage2\Framework\Support\BaseModule;
use Mage2\Framework\Module\Facades\Module as ModuleFacade;
use Illuminate\Support\Facades\File;
use Symfony\Component\Yaml\Yaml;

class Module extends BaseModule {

    /**
     *
     * Module Name Variable
     * @var name
     *
     */
    protected $name = NULL;

    /**
     *
     * Module Odentifier  Variable
     * @var identifier
     *
     */
    protected $identifier = NULL;
    /**
     *
     * Module Description Variable
     * @var description
     *
     */
    protected $description = NULL;

    /**
     *
     * Module Enable Variable
     * @var enable
     *
     */
    protected $enable = NULL;

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    //protected $defer = true;
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        $this->registerModule();
        $this->registerShippingOption();
        $this->registerAdminMenu();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {

        $this->registerModuleYamlFile(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'module.yaml');
        $this->registerViewPath();
    }


    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    protected function registerShippingOption() {
        $freeShipping = new FreeShipping();
        Shipping::put($freeShipping->getIdentifier(), $freeShipping);
    }

    protected function registerViewPath() {
        View::addLocation(__DIR__ . '/views');
    }

    public function registerAdminMenu() {
        //AdminMenu::registerMenu($adminMenu);
    }

    public function registerModule() {
        ModuleFacade::put($this->getIdentifier(), $this);
    }

    public function getNameSpace() {
        return __NAMESPACE__;
    }
    
    public function getPath() {
        return __DIR__;
    }

}
