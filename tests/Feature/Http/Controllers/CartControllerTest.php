<?php
namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\CartController;
use AvoRed\Framework\Models\Contracts\ConfigurationInterface;
use AvoRed\Framework\Models\Contracts\ProductInterface;
use AvoRed\Framework\Models\Database\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartControllerTest extends \Tests\TestCase
{
    use RefreshDatabase;

    /** @var ProductInterface|PHPUnit_Framework_MockObject_MockObject */
    private $productMock;
    /** @var ConfigurationInterface|PHPUnit_Framework_MockObject_MockObject */
    private $configMock;
    /** @var CartController */
    private $instance;

    public function setUp()
    {
        parent::setUp();
        return;
        $this->productMock = $this->getMockBuilder(ProductInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->configMock = $this->getMockBuilder(ConfigurationInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->instance = new CartController($this->productMock, $this->configMock);
    }

    /** @test */
    public function testCartViewRoute()
    {
        $response = $this->get(route('cart.view'));
        $response->assertStatus(200);
        $response->assertSee('There is no Product in Cart yet.');
    }
    /** @test */
    public function testCartViewWithProductRoute()
    {
        $product = factory(Product::class)->create();
        $data = ['slug' => $product->slug];
        
        $response = $this->post(route('cart.add-to-cart', $data));
        $response->assertStatus(302);
        $response->assertSessionHas('notificationText', 'Product Added to Cart Successfully!');
    }

    public function testSuccessfulAddToCartWithoutTax()
    {
        $this->markTestIncomplete();
        $slug = 'my-nice-slug';
        $qty = 1;
        $requestMock = $this->getMockBuilder(Illuminate\Http\Request::class)->disableOriginalConstructor()->getMock();
        $requestMock->expects($this->any())->method('get')->willReturnOnConsecutiveCalls($slug, $qty, []);
        \AvoRed\Framework\Cart\Facade::shouldReceive('canAddToCart')->once()->andReturn(true);
        \AvoRed\Framework\Cart\Facade::shouldReceive('add')->once()->andReturnSelf();
        /** @var Product|PHPUnit_Framework_MockObject_MockObject $modelMock */
        $modelMock = $this->getMockBuilder(Product::class)->disableOriginalConstructor()->getMock();
        $modelMock->setAttribute('is_taxable', false);
        $this->productMock->expects($this->exactly(1))->method('findBySlug')->with($slug)->willReturn($modelMock);
        $this->configMock->expects($this->exactly(1))->method('getValueByKey')->with('tax_enabled')->willReturn(false);
        $result = $this->instance->addToCart($requestMock);
        $successMsg = 'Product Added to Cart Successfully!';
        $this->assertEquals($successMsg, $result->getSession()->get('notificationText'));
        $this->assertEquals(302, $result->getStatusCode());
    }

    public function testSuccessfulAddToCartWithTaxCalculation()
    {
        $this->markTestIncomplete();
        $slug = 'my-nice-second-slug';
        $qty = 1;
        $requestMock = $this->getMockBuilder(Illuminate\Http\Request::class)->disableOriginalConstructor()->getMock();
        $requestMock->expects($this->any())->method('get')->willReturnOnConsecutiveCalls($slug, $qty, []);
        \AvoRed\Framework\Cart\Facade::shouldReceive('canAddToCart')->once()->andReturn(true);
        \AvoRed\Framework\Cart\Facade::shouldReceive('add')->once()->andReturnSelf();
        /** @var Product|PHPUnit_Framework_MockObject_MockObject $modelMock */
        $modelMock = $this->getMockBuilder(Product::class)->disableOriginalConstructor()->getMock();
        $modelMock->is_taxable = true;
        $modelMock->price = 200;
        $modelMock->expects($this->exactly(2))->method('__get')->willReturnOnConsecutiveCalls(true, 200);
        $this->productMock->expects($this->exactly(1))->method('findBySlug')->with($slug)->willReturn($modelMock);
        $this->configMock
            ->expects($this->exactly(2))
            ->method('getValueByKey')
            ->willReturnOnConsecutiveCalls(true, 20);

        \AvoRed\Framework\Cart\Facade::shouldReceive('hasTax')->once()->andReturnSelf();
        \AvoRed\Framework\Cart\Facade::shouldReceive('updateProductTax')->once()->with($slug, 40)->andReturnSelf();

        $result = $this->instance->addToCart($requestMock);
        $successMsg = 'Product Added to Cart Successfully!';
        $this->assertEquals($successMsg, $result->getSession()->get('notificationText'));
        $this->assertEquals(302, $result->getStatusCode());
    }

    public function testUnsuccessfulAddToCart()
    {
        $this->markTestIncomplete();
        $slug = 'my-nice-slug';
        $qty = 1;
        $requestMock = $this->getMockBuilder(Illuminate\Http\Request::class)->disableOriginalConstructor()->getMock();
        $requestMock->expects($this->any())->method('get')->willReturnOnConsecutiveCalls($slug, $qty, []);
        \AvoRed\Framework\Cart\Facade::shouldReceive('canAddToCart')->once()->andReturn(false);
        $result = $this->instance->addToCart($requestMock);
        $errorMsg = 'Not Enough Qty Available. Please with less qty or Contact site Administrator!';
        $this->assertEquals($errorMsg, $result->getSession()->get('errorNotificationText'));
        $this->assertEquals(302, $result->getStatusCode());
    }

    public function testSuccessfulUpdate()
    {
        $this->markTestIncomplete();
        $slug = 'my-nice-slug';
        $qty = 1;
        $requestMock = $this->getMockBuilder(Illuminate\Http\Request::class)->disableOriginalConstructor()->getMock();
        $requestMock->expects($this->any())->method('get')->willReturnOnConsecutiveCalls($slug, $qty, []);
        \AvoRed\Framework\Cart\Facade::shouldReceive('canAddToCart')->once()->andReturn(true);
        \AvoRed\Framework\Cart\Facade::shouldReceive('update')->once()->andReturnSelf();

        /** @var Product|PHPUnit_Framework_MockObject_MockObject $modelMock */
        $modelMock = $this->getMockBuilder(Product::class)->disableOriginalConstructor()->getMock();
        $modelMock->setAttribute('is_taxable', false);
        $this->productMock->expects($this->exactly(1))->method('findBySlug')->with($slug)->willReturn($modelMock);
        $this->configMock->expects($this->exactly(1))->method('getValueByKey')->with('tax_enabled')->willReturn(false);

        $result = $this->instance->update($requestMock);
        $this->assertEquals(302, $result->getStatusCode());
    }

    public function testDestroy()
    {
        $this->markTestIncomplete();
        $slug = "sluggy-slug-slug";
        AvoRed\Framework\Cart\Facade::shouldReceive('destroy')->once()->with($slug);
        $result = $this->instance->destroy($slug);
        $successMsg = 'Product has been removed from Cart!';
        $this->assertEquals($successMsg, $result->getSession()->get('notificationText'));
        $this->assertEquals(302, $result->getStatusCode());
    }
}
