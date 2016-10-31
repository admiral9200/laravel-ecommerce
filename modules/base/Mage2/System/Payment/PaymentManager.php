<?php

namespace Mage2\System\Payment;

use Illuminate\Support\Collection;

class PaymentManager
{
    public $paymentOption;

    public function __construct()
    {
        $this->paymentOption = Collection::make([]);
    }

    public function all()
    {
        return $this->paymentOption;
    }

    public function get($identifier)
    {
        return $this->paymentOption->get($identifier);
    }

    public function put($identifier, $class)
    {
        $this->paymentOption->put($identifier, $class);

        return $this;
    }
}
