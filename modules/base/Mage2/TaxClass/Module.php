<?php

namespace Mage2\TaxClass;

use Illuminate\Support\Facades\View;
use Mage2\System\View\Facades\AdminConfiguration;
use Mage2\System\View\Facades\AdminMenu;

use Mage2\Framework\Support\BaseModule;

class Module extends BaseModule
{
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
    public function boot()
    {
        $this->registerAdminConfiguration();
    }


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerViewPath();
    }

   

    protected function registerViewPath()
    {
        View::addLocation(__DIR__.'/views');
    }

    public function registerAdminConfiguration()
    {
        $adminConfigurations[] = [
            'title'       => 'Tax Configuration',
            'description' => 'Defined the amount of tax applied to product.',
            'edit_action' => ('admin.configuration.tax-class'),
        ];

        foreach ($adminConfigurations as $adminConfiguration) {
            AdminConfiguration::registerConfiguration($adminConfiguration);
        }
    }
}
