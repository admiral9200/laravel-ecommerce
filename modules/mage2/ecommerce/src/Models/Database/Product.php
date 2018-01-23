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
 * Do not edit or add to this file if you wish to upgrade Mage2 to newer
 * versions in the future. If you wish to customize Mage2 for your
 * needs please refer to http://mage2.website for more information.
 *
 * @author    Purvesh <ind.purvesh@gmail.com>
 * @copyright 2016-2017 Mage2
 * @license   https://www.gnu.org/licenses/gpl-3.0.en.html GNU General Public License v3.0
 */

namespace Mage2\Ecommerce\Models\Database;

use Illuminate\Support\Facades\Session;
use Mage2\Ecommerce\Image\LocalFile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class Product extends BaseModel
{

    protected $fillable = ['type', 'name', 'slug', 'sku', 'description',
        'status', 'in_stock', 'track_stock', 'qty', 'is_taxable', 'page_title', 'page_description'];

    public static function getCollection()
    {
        $model = new static;
        $products = $model->all();
        $productCollection = new ProductCollection();
        $productCollection->setCollection($products);
        return $productCollection;
    }

    public static function boot()
    {
        parent::boot();

        // registering a callback to be executed upon the creation of an activity AR
        static::creating(function ($model) {

            // produce a slug based on the activity title
            $slug = Str::slug($model->name);

            // check to see if any other slugs exist that are the same & count them
            $count = static::where("slug", "=", $slug)->count();

            // if other slugs exist that are the same, append the count to the slug
            $model->slug = $count ? "{$slug}-{$count}" : $slug;

        });

    }

    public function canAddtoCart($qty = 0) {
        $products = Session::get('cart');

        if(null == $products) {
            return true;
        }

        $productId = $this->attributes['id'];

        $cartProduct = $products->get($productId);

        $availableQty = $this->attributes['qty'];

        $currentCartQty = (isset($cartProduct['qty'])) ? $cartProduct['qty'] : 0;

        if($availableQty-$currentCartQty - $qty < 0) {
            return false;
        } else {
            return true;
        }

    }

    /**
     * Update the Product and Product Related Data
     *
     * @var \Mage2\Ecommerce\Http\Requests\ProductRequest $request
     * @return void
     */
    public function saveProduct($request)
    {

        //*****  SAVING PRODUCT BASIC FIELDS  *****//
        $this->update($request->all());


        //*****  SAVING PRODUCT PRICES  *****//
        if ($this->prices()->get()->count() > 0) {
            $this->prices()->get()->first()->update(['price' => $request->get('price')]);
        } else {
            $this->prices()->create(['price' => $request->get('price')]);
        }

        //*****  SAVING PRODUCT IMAGES  *****//
        if (null !== $request->get('image')) {
            $exitingIds = $this->images()->get()->pluck('id')->toArray();
            foreach ($request->get('image') as $key => $data) {
                if (is_int($key)) {
                    if (($findKey = array_search($key, $exitingIds)) !== false) {
                        $productImage = ProductImage::findorfail($key);
                        $productImage->update($data);
                        unset($exitingIds[$findKey]);
                    }
                    continue;
                }
                ProductImage::create($data + ['product_id' => $this->id]);
            }
            if (count($exitingIds) > 0) {
                ProductImage::destroy($exitingIds);
            }
        }

        //*****  SAVING PRODUCT CATEGORIES  *****//
        if (count($request->get('category_id')) > 0) {
            $this->categories()->sync($request->get('category_id'));
        }


        $properties = $request->get('property');


        foreach ($properties as $key => $property) {

            foreach ($property as $propertyId => $propertyValue) {

                $propertyModal = Property::findorfail($propertyId);

                if ($propertyModal->data_type == 'VARCHAR') {

                    $propertyVarcharValue = ProductPropertyVarcharValue::whereProductId($this->id)->wherePropertyId($propertyId)->get()->first();

                    if (null === $propertyVarcharValue) {
                        ProductPropertyVarcharValue::create([
                            'product_id' => $this->id,
                            'property_id' => $propertyId,
                            'value' => $propertyValue
                        ]);
                    } else {
                        $propertyVarcharValue->update(['value' => $propertyValue]);
                    }
                }

                if ($propertyModal->data_type == 'BOOLEAN') {

                    $propertyBooleanValue = ProductPropertyBooleanValue::whereProductId($this->id)->wherePropertyId($propertyId)->get()->first();

                    if (null === $propertyBooleanValue) {
                        ProductPropertyBooleanValue::create([
                            'product_id' => $this->id,
                            'property_id' => $propertyId,
                            'value' => $propertyValue
                        ]);
                    } else {
                        $propertyBooleanValue->update(['value' => $propertyValue]);
                    }
                }

                if ($propertyModal->data_type == 'TEXT') {

                    $propertyTextValue = ProductPropertyTextValue::whereProductId($this->id)->wherePropertyId($propertyId)->get()->first();

                    if (null === $propertyTextValue) {
                        ProductPropertyTextValue::create([
                            'product_id' => $this->id,
                            'property_id' => $propertyId,
                            'value' => $propertyValue
                        ]);
                    } else {
                        $propertyTextValue->update(['value' => $propertyValue]);
                    }
                }

                if ($propertyModal->data_type == 'DECIMAL') {

                    $propertyDecimalValue = ProductPropertyDecimalValue::whereProductId($this->id)->wherePropertyId($propertyId)->get()->first();

                    if (null === $propertyDecimalValue) {
                        ProductPropertyDecimalValue::create([
                            'product_id' => $this->id,
                            'property_id' => $propertyId,
                            'value' => $propertyValue
                        ]);
                    } else {
                        $propertyDecimalValue->update(['value' => $propertyValue]);
                    }
                }
                if ($propertyModal->data_type == 'INTEGER') {

                    $propertyIntegerValue = ProductPropertyIntegerValue::whereProductId($this->id)->wherePropertyId($propertyId)->get()->first();

                    if (null === $propertyIntegerValue) {
                        ProductPropertyIntegerValue::create([
                            'product_id' => $this->id,
                            'property_id' => $propertyId,
                            'value' => $propertyValue
                        ]);
                    } else {
                        $propertyIntegerValue->update(['value' => $propertyValue]);
                    }
                }


            }

        }


    }


    /**
     * Return Product model by Product Slug
     *
     * @param $slug
     * return \Mage2\Ecommerce\Models\Database\Product $product
     */
    public static function getProductBySlug($slug)
    {
        $model = new static;
        return $model->where('slug', '=', $slug)->first();
    }


    public function getReviews()
    {
        return $this->reviews()->where('status', '=', 'ENABLED')->get();
    }

    public function getAssignedVariableAttributes()
    {
        return $this->productAttributes;
        $attributes = Collection::make([]);

        $attributeValues = ProductAttributeValue::whereProductId($this->attributes['id'])->get();

        if($attributeValues->count() > 0) {

            foreach ($attributeValues as $attributeValue) {
                $attributes->push($attributeValue->attribute);
            }
        }

        return $attributes;
    }

    /**
     * return default Image or LocalFile Object
     *
     * @return \Mage2\Ecommerce\Image\LocalFile
     */
    public function getImageAttribute()
    {
        $defaultPath = "/img/default-product.jpg";
        $image = $this->images()->where('is_main_image', '=', 1)->first();


        if (null === $image) {
            return new LocalFile($defaultPath);
        }

        if ($image->path instanceof LocalFile) {
            return $image->path;
        }


    }

    public function getAssignedVariationBytAttributeId($attributeId)
    {
        return $this->productVariations()
            ->where('product_attribute_id', '=', $attributeId)
            ->get();
    }

    /*
     * Calculate Tax amount based on default country and return tax amount
     *
     * @return float $taxAmount
     */

    public function getTaxAmount($price = NULL)
    {


        $defaultCountryId = Configuration::getConfiguration('mage2_tax_class_default_country_for_tax_calculation');
        $taxRule = TaxRule::where('country_id', '=', $defaultCountryId)->orderBy('priority', 'DESC')->first();

        if (null === $price) {
            $price = $this->price;
        }

        if (null === $taxRule) {
            return 0.00;
        }
        $taxAmount = ($taxRule->percentage * $price / 100);

        return $taxAmount;
    }

    /*
    * Get the Price for the Product
    *
    * @return \Mage2\Ecommerce\Models\Database\Attribute
    */
    public function getSpecificationValue($attribute)
    {


        $productAttributeValue = $this->productAttributeValue()->whereAttributeId($attribute->id)->first();

        if (null !== $productAttributeValue) {
            if ($attribute->field_type == 'SELECT') {
                $selectedDropdownOption = $attribute->attributeDropdownOptions()->whereId($productAttributeValue->value)->first();
                return $selectedDropdownOption->id;

            } else {
                return $productAttributeValue->value;
            }

        }

        return "";
    }


    /*
     * Get the Product Assigned Property List
     *
     * @return \Mage2\Ecommerce\Models\Database\Property
     */
    public function getPropertyLists()
    {
        return [];
    }

    /*
     * Get the Price for the Product
     *
     * @return float $value
     */
    public function getPriceAttribute()
    {
        $row = $this->prices()->first();

        return (isset($row->price)) ? $row->price : null;
    }


    /*
     * Get the Product Property Value
     *
     * @return varchar $value
     */
    public function getPropertyValue(ProductPropertyVarcharValue $property)
    {
        dd($property);
        $row = $this->prices()->first();

        return (isset($row->price)) ? $row->price : null;
    }


    public function productAttributes()
    {
        return $this->belongsToMany(Attribute::class);
    }


    public function combinations()
    {
        $combinations = Collection::make([]);
        $model = new static;
        $productIds = ProductCombination::whereProductId($this->attributes['id'])->pluck('combination_id');
        foreach ($productIds as $id) {
            $combinations->push($model->findorfail($id));
        }

        return $combinations;
    }

    public function getCombinationAttributeList() {
        $attributes = Collection::make([]);
        $subProductIds = $this->combinations()->pluck('id');

        $productAttributeValues = ProductAttributeValue::whereIn('product_id',$subProductIds->toArray())->get();
        foreach ($productAttributeValues as $productAttributeValue) {
            $attributes->push($productAttributeValue->attribute);
        }
        return $attributes;
    }


    public function getCombinationAttributeValueList() {

        $subProductIds = $this->combinations()->pluck('id');
        $productAttributeValues = ProductAttributeValue::whereIn('product_id',$subProductIds->toArray())->get()->pluck('value');

        return $productAttributeValues;

    }


    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function prices()
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function relatedProducts()
    {
        return $this->hasMany(RelatedProduct::class);
    }

    public function productAttributeValue()
    {
        return $this->hasMany(ProductAttributeValue::class);
    }


    public function getProductAllProperties()
    {
        $collection = Collection::make([]);


        foreach ($this->productVarcharProperties as $item) {
            $collection->push($item);
        }
        foreach ($this->productBooleanProperties as $item) {
            $collection->push($item);
        }

        foreach ($this->productTextProperties as $item) {
            $collection->push($item);
        }
        foreach ($this->productDecimalProperties as $item) {
            $collection->push($item);
        }
        foreach ($this->productDecimalProperties as $item) {
            $collection->push($item);
        }
        foreach ($this->productIntegerProperties as $item) {
            $collection->push($item);
        }


        return $collection;
    }

    public function productVarcharProperties()
    {
        return $this->hasMany(ProductPropertyVarcharValue::class);
    }

    public function productBooleanProperties()
    {
        return $this->hasMany(ProductPropertyBooleanValue::class);
    }

    public function productIntegerProperties()
    {
        return $this->hasMany(ProductPropertyIntegerValue::class);
    }

    public function productTextProperties()
    {
        return $this->hasMany(ProductPropertyTextValue::class);
    }

    public function productDecimalProperties()
    {
        return $this->hasMany(ProductPropertyDecimalValue::class);
    }


    public function attribute()
    {
        return $this->belongsToMany(Attribute::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
