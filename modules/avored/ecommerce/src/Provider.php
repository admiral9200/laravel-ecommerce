<?php
namespace AvoRed\Ecommerce;

use Illuminate\Support\ServiceProvider;

use AvoRed\Ecommerce\Http\Middleware\AdminAuth;
use AvoRed\Ecommerce\Http\Middleware\AdminApiAuth;
use AvoRed\Ecommerce\Http\Middleware\ProductViewed;
use AvoRed\Ecommerce\Http\Middleware\Visitor;
use AvoRed\Ecommerce\Http\Middleware\RedirectIfAdminAuth;
use AvoRed\Ecommerce\Http\Middleware\FrontAuth;
use AvoRed\Ecommerce\Http\Middleware\Permission;
use AvoRed\Ecommerce\Http\Middleware\RedirectIfFrontAuth;

use AvoRed\Ecommerce\Http\ViewComposers\AdminNavComposer;
use AvoRed\Ecommerce\Http\ViewComposers\CategoryFieldsComposer;
use AvoRed\Ecommerce\Http\ViewComposers\LayoutAppComposer;
use AvoRed\Ecommerce\Http\ViewComposers\ProductFieldsComposer;
use AvoRed\Ecommerce\Http\ViewComposers\CheckoutComposer;
use AvoRed\Ecommerce\Http\ViewComposers\MyAccountSidebarComposer;

use Laravel\Passport\Passport;
use Laravel\Passport\Console\InstallCommand;
use Laravel\Passport\Console\ClientCommand;
use Laravel\Passport\Console\KeysCommand;
use Carbon\Carbon;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use AvoRed\Ecommerce\Http\ViewComposers\ProductSpecificationComposer;
use AvoRed\Ecommerce\Http\ViewComposers\RelatedProductViewComposer;
use Illuminate\Support\Facades\Storage;
use AvoRed\Ecommerce\Widget\TotalUser\Widget as TotalUserWidget;
use AvoRed\Ecommerce\Widget\TotalOrder\Widget as TotalOrderWidget;
use AvoRed\Framework\Widget\Facade as WidgetFacade;
use AvoRed\Framework\Breadcrumb\Facade as BreadcrumbFacade;
use AvoRed\Framework\AdminMenu\Facade as AdminMenuFacade;
use AvoRed\Framework\AdminMenu\AdminMenu;
use AvoRed\Ecommerce\Payment\Pickup\Payment as PickupPayment;
use AvoRed\Ecommerce\Payment\Stripe\Payment as StripePayment;
use AvoRed\Framework\Payment\Facade as PaymentFacade;
use AvoRed\Framework\Shipping\Facade as ShippingFacade;
use AvoRed\Ecommerce\Shipping\FreeShipping;
use AvoRed\Framework\Permission\Facade as PermissionFacade;
use Illuminate\Support\Facades\Blade;

class Provider extends ServiceProvider
{

    protected $providers = [
        //\AvoRed\Ecommerce\DataGrid\Provider::class,
        //\AvoRed\Ecommerce\Attribute\Provider::class,
        //\AvoRed\Ecommerce\Tabs\Provider::class,
        //\AvoRed\Ecommerce\Modules\Provider::class,
        //\AvoRed\Ecommerce\Payment\Provider::class,
        //\AvoRed\Ecommerce\Shipping\Provider::class,
        //\AvoRed\Ecommerce\Configuration\Provider::class,
        //\AvoRed\Ecommerce\Permission\Provider::class,
        //\AvoRed\Ecommerce\Theme\Provider::class,
        //\AvoRed\Ecommerce\Widget\Provider::class
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerMiddleware();
        $this->registerResources();
        $this->registerViewComposerData();
        $this->registerPassportResources();
        $this->registerWidget();
        $this->registerAdminMenu();
        $this->registerBreadcrumb();
        $this->registerPaymentOptions();
        $this->registerShippingOption();
        $this->registerPermissions();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerProviders();
        Passport::ignoreMigrations();

    }


    /**
     * Registering AvoRed E commerce Resource
     * e.g. Route, View, Database path & Translation
     *
     * @return void
     */
    protected function registerResources()
    {

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'avored-ecommerce');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'avored-ecommerce');
    }

    /**
     * Registering AvoRed E commerce Middleware
     *
     * @return void
     */
    protected function registerMiddleware()
    {
        $router = $this->app['router'];

        $router->aliasMiddleware('admin.api.auth', AdminApiAuth::class);
        $router->aliasMiddleware('admin.auth', AdminAuth::class);
        $router->aliasMiddleware('admin.guest', RedirectIfAdminAuth::class);
        $router->aliasMiddleware('front.auth', FrontAuth::class);
        $router->aliasMiddleware('front.guest', RedirectIfFrontAuth::class);
        $router->aliasMiddleware('visitor', Visitor::class);
        $router->aliasMiddleware('permission', Permission::class);
        $router->aliasMiddleware('product.viewed', ProductViewed::class);
    }

    /**
     * Registering Class Based View Composer
     *
     * @return void
     */
    public function registerViewComposerData()
    {
        View::composer('avored-ecommerce::admin.layouts.left-nav', AdminNavComposer::class);
        View::composer(['avored-ecommerce::admin.category._fields'], CategoryFieldsComposer::class);
        View::composer('checkout.index', CheckoutComposer::class);
        View::composer('user.my-account.sidebar', MyAccountSidebarComposer::class);
        View::composer('layouts.app', LayoutAppComposer::class);
        View::composer('catalog.product.view', RelatedProductViewComposer::class);
        View::composer('user.my-account.sidebar', MyAccountSidebarComposer::class);
        View::composer('avored-framework::product.edit', ProductFieldComposer::class);
        View::composer('catalog.product.view', ProductSpecificationComposer::class);
        View::composer(['avored-ecommerce::admin.product.create',
                        'avored-ecommerce::admin.product.edit'
                        ],  ProductFieldsComposer::class);

        View::composer(['avored-framework::product.create',
                        'avored-framework::product.edit'
                        ], RelatedProductComposer::class);
    }

    /**
     * Registering AvoRed E commerce Services
     * e.g Admin Menu
     *
     * @return void
     */
    protected function registerProviders()
    {
        if (!Storage::disk('local')->has('installed.txt')) {
            App::register(\AvoRed\Install\Module::class);
        }

        foreach ($this->providers as $provider) {
            App::register($provider);
        }
    }


    /*
   *  Registering Passport Oauth2.0 client
   *      *
   * @return void
   */
    public function registerPassportResources()
    {
        Passport::ignoreMigrations();
        Passport::routes();
        Passport::tokensExpireIn(Carbon::now()->addDays(15));
        $this->commands([
            InstallCommand::class,
            ClientCommand::class,
            KeysCommand::class,
        ]);
    }



    /**
     * Register the Menus.
     *
     * @return void
     */
    protected function registerAdminMenu() {
        AdminMenuFacade::add('catalog')
            ->label('Catalog')
            ->route('#')
            ->icon('fa fa-book');
        $catalogMenu = AdminMenuFacade::get('catalog');
        $productMenu = new AdminMenu();
        $productMenu->key('product')
            ->label('Product')
            ->route('admin.product.index')
            ->icon('fab fa-dropbox');
        $catalogMenu->subMenu('product', $productMenu);
        $categoryMenu = new AdminMenu();
        $categoryMenu->key('category')
            ->label('Category')
            ->route('admin.category.index')
            ->icon('far fa-building');
        $catalogMenu->subMenu('category', $categoryMenu);
        $attributeMenu = new AdminMenu();
        $attributeMenu->key('attribute')
            ->label('Attribute')
            ->route('admin.attribute.index')
            ->icon('fas fa-file-alt');
        $catalogMenu->subMenu('attribute',$attributeMenu);
        $propertyMenu = new AdminMenu();
        $propertyMenu->key('property')
            ->label('Property')
            ->route('admin.property.index')
            ->icon('fas fa-file-powerpoint');
        $catalogMenu->subMenu('property',$propertyMenu);
        AdminMenuFacade::add('promotion')
            ->label('Promotion')
            ->route('#')
            ->icon('fas fa-bullhorn');
        $promotionMenu = AdminMenuFacade::get('promotion');
        $subscriberMenu = new AdminMenu();
        $subscriberMenu->key('subscriber')
            ->label('Subscriber')
            ->route('admin.subscriber.index')
            ->icon('fas fa-users');
        $promotionMenu->subMenu('subscriber',$subscriberMenu);
        $pageMenu = new AdminMenu();
        $pageMenu->key('page')
            ->label('Page')
            ->route('admin.page.index');
        //$catalogMenu->subMenu('page', $pageMenu);
        $reviewMenu = new AdminMenu();
        $reviewMenu->key('review')
            ->label('Review')
            ->route('admin.review.index');
        //$catalogMenu->subMenu('review', $reviewMenu);
        AdminMenuFacade::add('sale')
            ->label('Sales')
            ->route('#')
            ->icon('fas fa-chart-line');
        $saleMenu = AdminMenuFacade::get('sale');
        AdminMenuFacade::add('system')
            ->label('System')
            ->route('#')
            ->icon('fas fa-cogs');
        $systemMenu = AdminMenuFacade::get('system');
        $configurationMenu = new AdminMenu();
        $configurationMenu->key('configuration')
            ->label('Configuration')
            ->route('admin.configuration')
            ->icon('fas fa-cog');
        $systemMenu->subMenu('configuration', $configurationMenu );
        $orderMenu = new AdminMenu();
        $orderMenu->key('order')
            ->label('Order')
            ->route('admin.order.index')
            ->icon('fas fa-dollar-sign');
        $saleMenu->subMenu('order', $orderMenu);
        $giftCouponMenu = new AdminMenu();
        $giftCouponMenu->key('gift-coupon')
            ->label('Gift Coupon')
            ->route('admin.gift-coupon.index');
        //$saleMenu->subMenu('gift-coupon', $giftCouponMenu );
        $orderStatusMenu = new AdminMenu();
        $orderStatusMenu->key('order-status')
            ->label('Order Status')
            ->route('admin.order-status.index');
        //$saleMenu->subMenu('order-status', $orderStatusMenu );
        $adminUserMenu = new AdminMenu();
        $adminUserMenu->key('admin-user')
            ->label('Admin User')
            ->route('admin.admin-user.index')
            ->icon('fas fa-user');
        $systemMenu->subMenu('admin-user',$adminUserMenu);
        $themeMenu = new AdminMenu();
        $themeMenu->key('themes')
            ->label('Themes ')
            ->route('admin.theme.index')
            ->icon('fas fa-adjust');
        $systemMenu->subMenu('themes',$themeMenu);
        $roleMenu = new AdminMenu();
        $roleMenu->key('roles')
            ->label('Role')
            ->route('admin.role.index')
            ->icon('fab fa-periscope');
        $systemMenu->subMenu('roles',$roleMenu);
        $taxGroupMenu = new AdminMenu();
        $taxGroupMenu->key('tax-group')
            ->label('Tax Group')
            ->route('admin.tax-group.index');
        //$saleMenu->subMenu('tax-group',$taxGroupMenu);
        $taxRuleMenu = new AdminMenu();
        $taxRuleMenu->key('tax-rule')
            ->label('Tax Rule')
            ->route('admin.tax-rule.index')
            ->icon('far fa-folder');
        $saleMenu->subMenu('tax-rule',$taxRuleMenu);
        $contryMenu = new AdminMenu();
        $contryMenu->key('countries')
            ->label('Countries')
            ->route('admin.country.index');
        //$saleMenu->subMenu('countries',$contryMenu);
        $stateMenu = new AdminMenu();
        $stateMenu->key('state')
            ->label('States')
            ->route('admin.state.index');
        //$saleMenu->subMenu('state',$stateMenu);
    }

    /**
     * Register the Menus.
     *
     * @return void
     */
    protected function registerBreadcrumb() {

        BreadcrumbFacade::make('admin.dashboard',function ($breadcrumb) {
            $breadcrumb->label('Dashboard');
        });

        BreadcrumbFacade::make('admin.product.index',function ($breadcrumb) {
            $breadcrumb->label('Product')
                ->parent('admin.dashboard');
        });

        BreadcrumbFacade::make('admin.product.create',function ($breadcrumb) {
            $breadcrumb->label('Create')
                ->parent('admin.dashboard')
                ->parent('admin.product.index');
        });

        BreadcrumbFacade::make('admin.product.edit',function ($breadcrumb) {
            $breadcrumb->label('Edit')
                ->parent('admin.dashboard')
                ->parent('admin.product.index');
        });

        BreadcrumbFacade::make('admin.attribute.index',function ($breadcrumb) {
            $breadcrumb->label('Attribute')
                ->parent('admin.dashboard');
        });

        BreadcrumbFacade::make('admin.attribute.create',function ($breadcrumb) {
            $breadcrumb->label('Create')
                ->parent('admin.dashboard')
                ->parent('admin.attribute.index');
        });

        BreadcrumbFacade::make('admin.attribute.edit',function ($breadcrumb) {
            $breadcrumb->label('Edit')
                ->parent('admin.dashboard')
                ->parent('admin.attribute.index');
        });


        BreadcrumbFacade::make('admin.property.index',function ($breadcrumb) {
            $breadcrumb->label('Property')
                ->parent('admin.dashboard');
        });

        BreadcrumbFacade::make('admin.property.create',function ($breadcrumb) {
            $breadcrumb->label('Create')
                ->parent('admin.dashboard')
                ->parent('admin.property.index');
        });

        BreadcrumbFacade::make('admin.attribute.edit',function ($breadcrumb) {
            $breadcrumb->label('Edit')
                ->parent('admin.dashboard')
                ->parent('admin.attribute.index');
        });



        BreadcrumbFacade::make('admin.subscriber.index',function ($breadcrumb) {
            $breadcrumb->label('Subscriber')
                ->parent('admin.dashboard');
        });

        BreadcrumbFacade::make('admin.subscriber.create',function ($breadcrumb) {
            $breadcrumb->label('Create')
                ->parent('admin.dashboard')
                ->parent('admin.subscriber.index');
        });

        BreadcrumbFacade::make('admin.subscriber.edit',function ($breadcrumb) {
            $breadcrumb->label('Edit')
                ->parent('admin.dashboard')
                ->parent('admin.subscriber.index');
        });



        BreadcrumbFacade::make('admin.order.index',function ($breadcrumb) {
            $breadcrumb->label('Order')
                ->parent('admin.dashboard');
        });

        BreadcrumbFacade::make('admin.order.view',function ($breadcrumb) {
            $breadcrumb->label('View')
                ->parent('admin.dashboard')
                ->parent('admin.order.index');;
        });

        BreadcrumbFacade::make('admin.theme.index',function ($breadcrumb) {
            $breadcrumb->label('Theme')
                ->parent('admin.dashboard');
        });

        BreadcrumbFacade::make('admin.theme.create',function ($breadcrumb) {
            $breadcrumb->label('Upload')
                ->parent('admin.dashboard')
                ->parent('admin.theme.index');
        });


        BreadcrumbFacade::make('admin.role.index',function ($breadcrumb) {
            $breadcrumb->label('Role')
                ->parent('admin.dashboard');
        });

        BreadcrumbFacade::make('admin.role.create',function ($breadcrumb) {
            $breadcrumb->label('Create')
                ->parent('admin.dashboard')
                ->parent('admin.role.index');
        });

        BreadcrumbFacade::make('admin.role.edit',function ($breadcrumb) {
            $breadcrumb->label('Edit')
                ->parent('admin.dashboard')
                ->parent('admin.role.index');
        });


        BreadcrumbFacade::make('admin.admin-user.index',function ($breadcrumb) {
            $breadcrumb->label('Admin User')
                ->parent('admin.dashboard');
        });

        BreadcrumbFacade::make('admin.admin-user.create',function ($breadcrumb) {
            $breadcrumb->label('Create')
                ->parent('admin.dashboard')
                ->parent('admin.admin-user.index');
        });

        BreadcrumbFacade::make('admin.admin-user.edit',function ($breadcrumb) {
            $breadcrumb->label('Edit')
                ->parent('admin.dashboard')
                ->parent('admin.admin-user.index');
        });

        BreadcrumbFacade::make('admin.admin-user.show',function ($breadcrumb) {
            $breadcrumb->label('Show')
                ->parent('admin.dashboard')
                ->parent('admin.admin-user.index');
        });



        BreadcrumbFacade::make('admin.tax-rule.index',function ($breadcrumb) {
            $breadcrumb->label('Tax Rule')
                ->parent('admin.dashboard');
        });

        BreadcrumbFacade::make('admin.tax-rule.create',function ($breadcrumb) {
            $breadcrumb->label('Create')
                ->parent('admin.dashboard')
                ->parent('admin.tax-rule.index');
        });

        BreadcrumbFacade::make('admin.tax-rule.edit',function ($breadcrumb) {
            $breadcrumb->label('Edit')
                ->parent('admin.dashboard')
                ->parent('admin.tax-rule.index');
        });

        BreadcrumbFacade::make('admin.configuration',function ($breadcrumb) {
            $breadcrumb->label('Configuration')
                ->parent('admin.dashboard');
        });


        BreadcrumbFacade::make('admin.category.index',function ($breadcrumb) {
            $breadcrumb->label('Category')
                ->parent('admin.dashboard');
        });

        BreadcrumbFacade::make('admin.category.create',function ($breadcrumb) {
            $breadcrumb->label('Create')
                ->parent('admin.dashboard')
                ->parent('admin.category.index');
        });

        BreadcrumbFacade::make('admin.category.edit',function ($breadcrumb) {
            $breadcrumb->label('Edit')
                ->parent('admin.dashboard')
                ->parent('admin.category.index');
        });


    }

    /**
     * Registering PAyment Option for the App.
     *
     *
     * @return void
     */
    protected function registerPaymentOptions()
    {
        $pickup = new PickupPayment();
        PaymentFacade::put($pickup->getIdentifier(), $pickup);

        //$paypal = new PaypalPayment();
        //PaymentFacade::put($paypal->getIdentifier(), $paypal);

        $stripe = new StripePayment();
        PaymentFacade::put($stripe->getIdentifier(), $stripe);
    }


    /**
     * Register Shippiong Option for App.
     *
     * @return void
     */
    protected function registerShippingOption()
    {
        $freeShipping = new FreeShipping();
        ShippingFacade::put($freeShipping->getIdentifier(), $freeShipping);
    }


    /**
     * Register the permissions
     *
     * @return void
     */
    protected function registerPermissions()
    {

        $permissionGroup = PermissionFacade::add('category')
            ->label('Category Permissions');


        $permissionGroup->addPermission('admin-category-list')
            ->label('Category List')
            ->routes('admin.category.index');

        $permissionGroup->addPermission('admin-category-create')
            ->label('Create Category')
            ->routes('admin.category.create,admin.category.store');

        $permissionGroup->addPermission('admin-category-update')
            ->label('Update Category')
            ->routes('admin.category.edit,admin.category.update');

        $permissionGroup->addPermission('admin-category-destroy')
            ->label('Destroy Category')
            ->routes('admin.category.destroy');




        $permissionGroup = PermissionFacade::add('product')
            ->label('Product Permissions');

        $permissionGroup->addPermission('admin-product-list')
            ->label('Product List')
            ->routes('admin.product.index');
        $permissionGroup->addPermission('admin-product-create')
            ->label('Create Product')
            ->routes('admin.product.create,admin.product.store');
        $permissionGroup->addPermission('admin-product-update')
            ->label('Update Product')
            ->routes('admin.product.edit,admin.product.update');
        $permissionGroup->addPermission('admin-product-destroy')
            ->label('Destroy Product')
            ->routes('admin.product.destroy');

        $permissionGroup = PermissionFacade::add('attribute')
            ->label('Attribute Permissions');

        $permissionGroup->addPermission('admin-attribute-list')
            ->label('Attribute List')
            ->routes('admin.attribute.index');
        $permissionGroup->addPermission('admin-attribute-create')
            ->label('Attribute')
            ->routes('admin.attribute.create,admin.attribute.store');
        $permissionGroup->addPermission('admin-attribute-update')
            ->label('Attribute')
            ->routes('admin.attribute.edit,admin.attribute.update');
        $permissionGroup->addPermission('admin-attribute-destroy')
            ->label('Attribute')
            ->routes('admin.attribute.destroy');


        $permissionGroup = PermissionFacade::add('property')
            ->label('Attribute Permissions');

        $permissionGroup->addPermission('admin-property-list')
            ->label('Property List')
            ->routes('admin.property.index');
        $permissionGroup->addPermission('admin-property-create')
            ->label('Property Create')
            ->routes('admin.property.create,admin.property.store');
        $permissionGroup->addPermission('admin-attribute-update')
            ->label('Property Update')
            ->routes('admin.property.edit,admin.property.update');
        $permissionGroup->addPermission('admin-property-destroy')
            ->label('Property Destroy')
            ->routes('admin.property.destroy');


        //
        $permissionGroup = PermissionFacade::add('subscriber')
            ->label('Subscriber Permissions');

        $permissionGroup->addPermission('admin-subscriber-list')
            ->label('Subscriber List')
            ->routes('admin.subscriber.index');

        $permissionGroup->addPermission('admin-subscriber-create')
            ->label('Subscriber Create')
            ->routes('admin.subscriber.create,admin.subscriber.store');

        $permissionGroup->addPermission('admin-subscriber-update')
            ->label('Subscriber Update')
            ->routes('admin.subscriber.edit,admin.subscriber.update');

        $permissionGroup->addPermission('admin-subscriber-destroy')
            ->label('Subscriber Destroy')
            ->routes('admin.subscriber.destroy');


        $permissionGroup = PermissionFacade::add('tax-rule')
            ->label('Tax Rule Permissions');

        $permissionGroup->addPermission('admin-tax-rule-list')
            ->label('Tax Rule List')
            ->routes('admin.tax-rule.index');

        $permissionGroup->addPermission('admin-tax-rule-create')
            ->label('Tax Rule Create')
            ->routes('admin.tax-rule.create,admin.tax-rule.store');

        $permissionGroup->addPermission('admin-tax-rule-update')
            ->label('Tax Rule Update')
            ->routes('admin.tax-rule.edit,admin.tax-rule.update');

        $permissionGroup->addPermission('admin-tax-rule-destroy')
            ->label('Tax Rule Destroy')
            ->routes('admin.tax-rule.destroy');



        $permissionGroup = PermissionFacade::add('admin-user')
            ->label('Admin User Permissions');

        $permissionGroup->addPermission('admin-admin-user-list')
            ->label('Admin User List')
            ->routes('admin.admin-user.index');

        $permissionGroup->addPermission('admin-admin-user-create')
            ->label('Admin User Create')
            ->routes('admin.admin-user.create,admin.admin-user.store');

        $permissionGroup->addPermission('admin-admin-user-update')
            ->label('Admin User Update')
            ->routes('admin.admin-user.edit,admin.admin-user.update');

        $permissionGroup->addPermission('admin-admin-user-destroy')
            ->label('Admin User Destroy')
            ->routes('admin.admin-user.destroy');


        $permissionGroup = PermissionFacade::add('role')
            ->label('Role Permissions');

        $permissionGroup->addPermission('admin-role-list')
            ->label('Role List')
            ->routes('admin.role.index');

        $permissionGroup->addPermission('admin-role-create')
            ->label('Role Create')
            ->routes('admin.role.create,admin.role.store');

        $permissionGroup->addPermission('admin-role-update')
            ->label('Role Update')
            ->routes('admin.role.edit,admin.role.update');

        $permissionGroup->addPermission('admin-role-destroy')
            ->label('Role Destroy')
            ->routes('admin.role.destroy');


        $permissionGroup = PermissionFacade::add('role')
            ->label('Theme Permissions');

        $permissionGroup->addPermission('admin-theme-list')
            ->label('Theme List')
            ->routes('admin.theme.index');

        $permissionGroup->addPermission('admin-theme-create')
            ->label('Theme Upload/Create')
            ->routes('admin.create.index','admin.theme.store');

        $permissionGroup->addPermission('admin-theme-activated')
            ->label('Theme Activated')
            ->routes('admin.activated.index');

        $permissionGroup->addPermission('admin-theme-deactivated')
            ->label('Theme Deactivated')
            ->routes('admin.deactivated.index');

        $permissionGroup->addPermission('admin-theme-destroy')
            ->label('Theme Destroy')
            ->routes('admin.destroy.index');


        $permissionGroup = PermissionFacade::add('configuration')
            ->label('Configuration Permissions');

        $permissionGroup->addPermission('admin-configuration')
            ->label('Configuration')
            ->routes('admin.configuration');


        $permissionGroup->addPermission('admin-configuration-store')
            ->label('Configuration Store')
            ->routes('admin.configuration.store');



        $permissionGroup = PermissionFacade::add('order')
            ->label('Order Permissions');

        $permissionGroup->addPermission('admin-order-list')
            ->label('Order List')
            ->routes('admin.order.index');


        $permissionGroup->addPermission('admin-order-view')
            ->label('Order View')
            ->routes('admin.order.view');

        $permissionGroup->addPermission('admin-order-send-invoice-email')
            ->label('Order Sent Invoice By Email')
            ->routes('admin.order.send-email-invoice');

        $permissionGroup->addPermission('admin-order-change-status')
            ->label('Order Change Status')
            ->routes('admin.order.change-status,admin.order.update-status');


        Blade::if('hasPermission', function ($routeName) {

            $condition = false;
            $user = Auth::guard('admin')->user();


            if (!$user) {
                $condition = $user->hasPermission($routeName) ?: false;
            }

            $converted_res = ($condition) ? 'true' : 'false';


            return "<?php if ($converted_res): ?>";

        });

    }

    /**
     * Register the Widget.
     *
     * @return void
     */
    protected function registerWidget()
    {

        $totalUserWidget = new TotalUserWidget();
        WidgetFacade::make($totalUserWidget->identifier(), $totalUserWidget);

        $totalOrderWidget = new TotalOrderWidget();
        WidgetFacade::make($totalOrderWidget->identifier(), $totalOrderWidget);

    }
}