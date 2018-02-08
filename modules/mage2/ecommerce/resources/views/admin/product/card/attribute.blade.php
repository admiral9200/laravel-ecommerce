<?php
$productVariations = $model->productVariations;
$productAttributes = $model->getProductAllAttributes();
?>



<div class="row">

    <div class="col-12">


        @if($attributeOptions !== null && $attributeOptions->count() >= 0)

            <div class="input-group mb-3">

                <select class="form-control attribute-dropdown-element select2" multiple name="attribute_selected[]" style="width: 88%">
                    @foreach($attributeOptions as $value => $label)
                        <option
                                @if($productAttributes->contains('attribute_id',$value))
                                selected
                                @endif

                                value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                <div class="input-group-append">
                    <button data-token="{{ csrf_token() }}" data-product-id="{{ $model->id }}"
                            class="btn btn-warning use-selected-attribute"
                            type="button">Use Selected
                    </button>
                </div>
            </div>



            @if(null !== $productVariations && $productVariations->count() > 0  && false)

                <div class="product-variation-wrapper">


                    <div class="clearfix mb-3">
                        <a href="#" class="btn add-variant-button float-right  btn-primary">
                            <i class="fa fa-plus"></i> Add Variant
                        </a>
                    </div>


                    <div class="product-variation-card-wrapper   row">

                        @foreach($productVariations as $productVariation)
                        <div class="col-md-6 mb-3 single-card">

                            <button type="button" class="remove-variation-card">
                                <span aria-hidden="true">&times;</span>
                            </button>

                            <div class="card clearfix pt-2">
                                <div class="card-body">



                                    <?php
                                    $variationModel = $productVariation->variation;
                                    //dd($variationModel);

                                    $variationAttributes = $variationModel->getProductAllAttributes($productVariation);
                                    $tmpString = str_random();

                                    //dd($variationAttributes);

                                    ?>

                                    @foreach($variationAttributes as $productAttributeValue)

                                    <?php

                                                //var_dump($productAttributeValue);

                                        //$variationModel = $productAttributeValue->product;

                                        $attribute =  $productAttributeValue->attribute;

                                    ?>

                                    <input type="hidden" name="" value="{{ $variationModel->id }}" />
                                        @if($attribute->field_type == 'TEXT')
                                            <div class="form-group">
                                                <label for="attribute-{{ $tmpString }}-{{ $attribute->id }}">{{ $attribute->name }}</label>
                                                <input type="text"
                                                       name="attribute[{{ $tmpString }}][{{ $attribute->id  }}][value]"
                                                       class="form-control"
                                                       id=attribute-{{ $tmpString }}-{{ $attribute->id }}"
                                                />
                                            </div>
                                        @endif

                                        @if($attribute->field_type == 'CHECKBOX')
                                            <div class="form-check">

                                                <input type="hidden"
                                                       name="attribute[{{ $tmpString }}][{{ $attribute->id  }}][value]"
                                                       value="0"
                                                />

                                                <input type="checkbox"
                                                       name="attribute[{{ $tmpString }}][{{ $attribute->id  }}][value]"
                                                       class="form-check-input"
                                                       value="1"
                                                       id=attribute-{{ $tmpString }}-{{ $attribute->id }}"
                                                />
                                                <label class="form-check-label"
                                                       for="attribute-{{ $tmpString }}-{{ $attribute->id }}">
                                                    {{ $attribute->name }}
                                                </label>
                                            </div>
                                        @endif

                                        @if($attribute->field_type == 'TEXTAREA')
                                            <div class="form-group">
                                                <label for="attribute-{{ $tmpString }}-{{ $attribute->id }}">{{ $attribute->name }}</label>

                                                <textarea
                                                        name="attribute[{{ $tmpString }}][{{ $attribute->id  }}][value]"
                                                        class="form-control"
                                                        id=attribute-{{ $tmpString }}-{{ $attribute->id }}"></textarea>

                                            </div>
                                        @endif

                                        @if($attribute->field_type == 'SELECT')
                                            <div class="form-group">
                                                <label for="attribute-{{ $tmpString }}-{{ $attribute->id }}">{{ $attribute->name }}</label>

                                                <select name="attribute[{{ $tmpString }}][{{ $attribute->id  }}][value]"
                                                        class="form-control"
                                                        id=attribute-{{ $tmpString }}-{{ $attribute->id }}">

                                                    @foreach($attribute->attributeDropdownOptions as $option)


                                                        <option value="{{ $option->id }}"

                                                                @if($productAttributeValue->value == $option->id)
                                                                selected
                                                                @endif
                                                        >
                                                            {{ $option->display_text }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        @endif

                                        @if($attribute->field_type == 'DATETIME')
                                            <div class="form-group">
                                                <label for="attribute-{{ $tmpString }}-{{ $attribute->id }}">{{ $attribute->name }}</label>
                                                <input type="text"
                                                       name="attribute[{{ $tmpString }}][{{ $attribute->id  }}][value]"
                                                       class="form-control datetime"
                                                       id=attribute-{{ $tmpString }}-{{ $attribute->id }}"
                                                />
                                            </div>

                                        @endif

                                    @endforeach

                                        <div class="form-group">
                                            <label for="attribute-{{ $tmpString }}-price">Price Variation</label>
                                            <input type="text"
                                                   name="attribute[{{ $tmpString }}][{{ $attribute->id  }}][price]"
                                                   class="form-control"
                                                   value="{{ $variationModel->price }}"
                                                   id=attribute-{{ $tmpString }}-price"
                                            />
                                        </div>

                                        <div class="form-group">
                                            <label for="attribute-{{ $tmpString }}-qty">Qty</label>
                                            <input type="text"
                                                   name="attribute[{{ $tmpString }}][{{ $attribute->id  }}][qty]"
                                                   class="form-control"
                                                   value="{{ $variationModel->qty }}"
                                                   id=attribute-{{ $tmpString }}-qty"
                                            />
                                        </div>


                                </div>
                            </div>
                        </div>

                        @endforeach
                    </div>

                </div>


            <div class="card">
                <div class="card-header">Attribute Options: </div>
                <div class="card-body">
                    <div class="form-group">
                        <label></label>
                    </div>
                </div>
            </div>



            @else
                <div class="product-variation-wrapper d-none">


                    <div class="clearfix mb-3">
                        <a href="#" class="btn add-variant-button float-right  btn-primary">
                            <i class="fa fa-plus"></i> Add Variant
                        </a>
                    </div>


                    <div class="product-variation-card-wrapper   row">


                    </div>

                </div>

            @endif
        @endif
    </div>

</div>


@push('scripts')
    <script>
        $(function () {




            jQuery(document).on('click', '.remove-option', function (e) {
                e.preventDefault();
                e.stopPropagation();

                if (jQuery(this).parents('.option-tag-list:first').find('.single-option').length > 1) {
                    jQuery(this).parents(".single-option:first").remove();
                } else {
                    alert('You cannot remove all possible Options');
                }


            });


            jQuery('.use-selected-attribute').click(function (e) {
                e.preventDefault();
                e.stopPropagation();

                var elementValue = jQuery('.attribute-dropdown-element').val();
                var productId = jQuery(this).attr('data-product-id');


                var data = {_token: jQuery(this).attr('data-token'), attribute_id: elementValue, product_id: productId};


                jQuery.ajax({
                    url: '{{ route('admin.attribute.element') }}',
                    data: data,
                    method: 'post',
                    dataType: 'json',
                    success: function (response) {
                        if (response.success == true) {

                            jQuery('.product-variation-card-wrapper').html(response.content);


                            jQuery('.product-variation-wrapper').toggleClass('d-none');

                        }
                    }
                });
            })
        })


    </script>

@endpush