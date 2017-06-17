<?php

namespace Mage2\Framework\Shipping;

use Illuminate\Support\Collection;

class ShippingManager
{
    public $shippingOption;

    public function __construct()
    {
        $this->shippingOption = Collection::make([]);
    }

    public function all()
    {
        return $this->shippingOption;
    }

    public function get($identifier)
    {
        return $this->shippingOption->get($identifier);
    }

    public function put($identifier, $class)
    {
        $this->shippingOption->put($identifier, $class);

        return $this;
    }
}
