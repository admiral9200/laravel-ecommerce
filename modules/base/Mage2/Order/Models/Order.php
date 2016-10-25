<?php

namespace Mage2\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Mage2\Address\Models\Address;
use Mage2\Catalog\Models\Product;

class Order extends Model
{
    protected $fillable = [
                    'shipping_address_id',
                    'billing_address_id',
                    'user_id',
                    'shipping_method',
                    'payment_method',
                    'order_status_id',
                ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_order')->withPivot('price', 'qty');
    }

    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function getShippingAddressAttribute()
    {
        $shippingAddress = Address::findorfail($this->attributes['shipping_address_id']);

        return $shippingAddress;
    }

    public function getBillingAddressAttribute()
    {
        $address = Address::findorfail($this->attributes['billing_address_id']);

        return $address;
    }
}
