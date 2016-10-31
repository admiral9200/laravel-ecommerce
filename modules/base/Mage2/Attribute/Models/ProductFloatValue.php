<?php

namespace Mage2\Attribute\Models;

use Mage2\System\Models\BaseModel;

class ProductFloatValue extends BaseModel
{
    protected $fillable = ['website_id', 'product_id', 'attribute_id', 'value'];

    public function productAttribute()
    {
        $this->belongsTo(ProductAttribute::class);
    }
}
