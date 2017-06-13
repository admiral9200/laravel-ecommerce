<?php
/**
 * Mage2
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to ind.purvesh@gmail.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://mage2.website for more information.
 *
 * @author    Purvesh <ind.purvesh@gmail.com>
 * @copyright 2016-2017 Mage2
 * @license   https://www.gnu.org/licenses/gpl-3.0.en.html GNU General Public License v3.0
 */
namespace Mage2\Catalog\Models;

use Mage2\Framework\System\Models\BaseModel;

class ProductVariation extends BaseModel
{
    protected $fillable = ['product_id', 'sub_product_id', 'product_attribute_id', 'attribute_dropdown_option_id'];

    /**
     * Variation belongs to Product
     *
     * @return \Mage2\Catalog\Models\Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Variation belongs to Product
     *
     * @return \Mage2\Catalog\Models\Product
     */
    public function subProduct()
    {
        return $this->belongsTo(Product::class, 'sub_product_id');
    }

    /**
     * Variation belongs to Product
     *
     * @return \Mage2\Catalog\Models\Product
     */
    public function productAttribute()
    {
        return $this->belongsTo(ProductAttribute::class);
    }

    /**
     * Variation belongs to Attribute Dropdown Option.
     *
     * @return \Mage2\Catalog\Models\AttributeDropdownOption
     */
    public function attributeDropdownOption()
    {
        return $this->belongsTo(AttributeDropdownOption::class);
    }
}
