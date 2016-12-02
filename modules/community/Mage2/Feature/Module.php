<?php

namespace Mage2\Feature;

use Illuminate\Support\Facades\View;
use Mage2\Framework\Configuration\Facades\AdminConfiguration;
use Mage2\Framework\Support\BaseModule;
use Mage2\Framework\Module\Facades\Module as ModuleFacade;

class Module extends BaseModule {

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        $this->registerModule();
        //$this->registerAdminConfiguration();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        $this->mapWebRoutes();
        $this->registerAdminConfiguration();
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
    protected function mapWebRoutes() {
        require __DIR__ . '/routes/web.php';
    }

    /**
     * add path to view finder.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    protected function registerViewPath() {
        View::addLocation(__DIR__ . '/views');
    }

    /**
     * Register Admin Configuration for the Address Modules
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function registerAdminConfiguration() {
        $adminConfigurations[] = [
            'title' => 'Address Configuration',
            'description' => 'Set Default Country for Store',
            'edit_action' => 'admin.configuration.address',
        ];

        foreach ($adminConfigurations as $adminConfiguration) {
            AdminConfiguration::registerConfiguration($adminConfiguration);
        }
    }

    public function registerModule() {
        ModuleFacade::put($this->getIdentifier(), $this);
    }

    public function getName() {
        return 'Mage2 Feature Product';
    }

    public function getIdentifier() {
        return 'mage2-feature';
    }

}
