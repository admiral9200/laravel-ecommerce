<?php

namespace Mage2\Paypal;

use Illuminate\Support\Facades\View;
use Mage2\Framework\Payment\Facades\Payment;
use Mage2\Framework\Configuration\Facades\AdminConfiguration;
use Mage2\Paypal\Payment\Paypal;
use Mage2\Framework\Support\BaseModule;
use Mage2\Framework\Module\Facades\Module as ModuleFacade;

class Module extends BaseModule
{
     /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    //protected $defer = true;


    public function boot(){
        $this->registerModule();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->mapWebRoutes();
        $this->registerAdminConfiguration();
        $this->registerPaymentMethod();
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
    protected function mapWebRoutes()
    {
        require __DIR__.'/routes/web.php';
    }

    protected function registerPaymentMethod()
    {
        $paypal = new Paypal();
        Payment::put($paypal->getIdentifier(), $paypal);
    }

    protected function registerViewPath()
    {
        View::addLocation(__DIR__.'/views');
    }


    public function registerAdminConfiguration()
    {
        $adminConfigurations[] = [
            'title'       => 'Paypal Configuration',
            'description' => 'Some Description for Catalog Modules',
            'edit_action' => 'admin.configuration.paypal',
        ];

        foreach ($adminConfigurations as $adminConfiguration) {
            AdminConfiguration::registerConfiguration($adminConfiguration);
        }
    }

    public function registerModule() {
        ModuleFacade::put($this->getIdentifier(), $this, $type = 'system');
    }


    public function getName() {
        return 'Mage2 Payment';
    }

    public function getIdentifier() {
        return 'mage2-payment';
    }
}
