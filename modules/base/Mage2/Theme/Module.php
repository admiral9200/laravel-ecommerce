<?php

namespace Mage2\Theme;

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
        $this->registerAdminMenu();
        $this->registerAdminConfiguration();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mapWebRoutes();

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
        require __DIR__.'/routes.php';
    }

    protected function registerViewPath()
    {
        View::addLocation(__DIR__.'/views');
    }

    public function registerAdminMenu()
    {
        $adminMenu = [
            'label' => 'Themes',
            'url'   => route('admin.theme.index'),
        ];
        AdminMenu::registerMenu($adminMenu);
    }

    public function registerAdminConfiguration()
    {
        //$adminConfigurations[] = [
        //     'title'       => 'Theme Configuration',
        //     'description' => 'Defined the amount of tax applied to product.',
        //     'edit_action' => route('admin.configuration.theme'),
         //];

        //foreach ($adminConfigurations as $adminConfiguration) {
            //AdminConfiguration::registerConfiguration($adminConfiguration);
        //}
    }
}
