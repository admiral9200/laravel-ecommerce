<div class="row">
    <div class="col-6">
        @include('mage2-ecommerce::forms.text',['name' => 'name','label' => 'Name'])
    </div>
    <div class="col-6">
        @if(!isset($productCategories))
            <?php $productCategories = []; ?>
        @endif

            @include('mage2-ecommerce::forms.select2',['name' => 'category_id[]',
                                                    'label' => 'Category',
                                                    'attributes' => ['class' => 'form-control select2',
                                                                    'id' => 'category_id',
                                                                    'multiple' => true,
                                                                    ],
                                                    'options' => $categoryOptions,
                                                    'values' => $productCategories])


    </div>
</div>


<div class="row">
    <div class="col-6">
        @if(isset($editMethod) && $editMethod)
            @include('mage2-ecommerce::forms.text',['name' => 'slug','label' => 'Slug'])
        @endif
    </div>
    <div class="col-6">
        @include('mage2-ecommerce::forms.text',['name' => 'sku','label' => 'Sku'])
    </div>
</div>

@include('mage2-ecommerce::forms.textarea',['name' => 'description','label' => 'Description',
                                            'attributes' => ['class' => 'ckeditor','id' => 'description']])

<div class="row">
    @if($model->type == "VARIATION")
    <div class="col-6">
        @include('mage2-ecommerce::forms.text',['name' => 'price','label' => 'Base Price'])
    </div>
    @else
        <div class="col-6">
            @include('mage2-ecommerce::forms.text',['name' => 'price','label' => 'Price'])
        </div>
    @endif
    <div class="col-6">
        @include('mage2-ecommerce::forms.select',['name' => 'status','label' => 'Status', 'options' => ['1' => 'Enabled','0' => 'Disabled']])
    </div>
</div>


<div class="row">
    <div class="col-6">
        @include('mage2-ecommerce::forms.text',['name' => 'qty','label' => 'Qty'])
    </div>
    <div class="col-6">
        @include('mage2-ecommerce::forms.select',['name' => 'in_stock','label' => 'In Stock', 'options' => ['1' => 'Enabled','0' => 'Disabled']])
    </div>
</div>

<div class="row">
    <div class="col-6">
        @include('mage2-ecommerce::forms.select',['name' => 'track_stock','label' => 'Track Stock', 'options' => ['1' => 'Enabled','0' => 'Disabled']])

    </div>
    <div class="col-6">
        @include('mage2-ecommerce::forms.select',['name' => 'is_taxable','label' => 'Is taxable', 'options' => ['1' => 'Enabled','0' => 'Disabled']])
    </div>
</div>

@foreach(Attributes::all('basic-product') as $attribute)
    <?php

    ?>
    {!! $attribute->render() !!}
@endforeach

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/1.0.0-alpha.2/classic/ckeditor.js"></script>
    <script>

        ClassicEditor
            .create( document.querySelector( '.ckeditor' ) )
            .then( editor => {
            console.log( editor );
        } )
        .catch( error => {
            console.error( error );
        } );
    </script>


@endpush