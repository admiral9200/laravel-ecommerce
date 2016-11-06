<?php

namespace Mage2\Theme;

use Illuminate\Support\Facades\View;
use Mage2\System\View\Facades\AdminMenu;
use Mage2\Framework\Support\BaseModule;
use Mage2\Framework\Support\Facades\Permission;

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
        $this->registerPermissions();
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

    protected function registerViewPath()
    {
        View::addLocation(__DIR__.'/views');
    }

    public function registerAdminMenu()
    {
        $adminMenu = [
            'label' => 'Themes',
            'route'   => 'admin.theme.index',
        ];
        AdminMenu::registerMenu($adminMenu);
    }
    
       
   /**
     *  Register Permission for the roles
     *
     * @return void
     */
    protected function registerPermissions() {
        $permissions = [
            ['title' => 'Theme List',     'routes' => 'admin.theme.index'],
            ['title' => 'Theme Upload',   'routes' => "admin.theme.create,admin.theme.store"],
            ['title' => 'Theme Activate',   'routes' => "admin.theme.activate"],
            ['title' => 'Theme Destroy',  'routes' => "admin.theme.destroy"],
            
          
        ];

        foreach ($permissions as $permission) {
            Permission::add($permission);
        }
    }
}
