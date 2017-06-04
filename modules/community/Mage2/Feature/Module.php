<?php

namespace Mage2\Feature;

use Illuminate\Support\Facades\View;
use Mage2\Framework\Support\BaseModule;
use Mage2\Framework\Module\Facades\Module as ModuleFacade;
use Illuminate\Support\Facades\File;
use Symfony\Component\Yaml\Yaml;

class Module extends BaseModule
{

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
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(true === $this->getEnable()) {
            $this->registerModule();
        }

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerModuleYamlFile(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'module.yaml');

        if(true === $this->getEnable()) {
            $this->mapWebRoutes();
            $this->registerViewPath();
        }
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     *
     * @return void
     */
    protected function mapWebRoutes()
    {

        require __DIR__ . '/routes/web.php';

    }

    /**
     * add path to view finder.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    protected function registerViewPath()
    {
        View::addLocation(__DIR__ . '/views');
    }


    public function registerModule()
    {
        ModuleFacade::put($this->getIdentifier(), $this, $type = "community");
    }

    public function getNameSpace()
    {
        return __NAMESPACE__;
    }

    public function getPath()
    {
        return __DIR__;
    }

}
