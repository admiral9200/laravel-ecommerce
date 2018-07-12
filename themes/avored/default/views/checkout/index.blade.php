@extends('layouts.app')

@section('meta_title','Checkout Page AvoRed E commerce')
@section('meta_description','Checkout Page AvoRed E commerce')


@section('content')

    <div class="container">
        <div class="h1">Checkout</div>

        @if(is_null($cartItems))
            <p>Sorry No Product Found <a href="{{ route('home') }}">Start Shopping</a></p>
        @else

            <form id="place-order-form" method="post" action="{{ route('order.place') }}">
                @csrf
                <div class="row">
                    <div class="col-6">

                        @include('checkout.cards.personal')

                        @include('checkout.cards.address')
                    </div>


                    <div class="col-6">

                        @include('checkout.cards.shipping-options')

                        @include('checkout.cards.payment-options')

                        @include('checkout.cards.shopping-cart')

                        <div class="card mb-3">
                            <div class="card-header">
                                Your Comment
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <textarea name="comment" rows="3" class="form-control"></textarea>

                                </div>

                                <div class="text-left form-check">

                                    <input id="agree" type="checkbox"
                                           class="form-check-input{{  $errors->has('agree') ? " is-invalid" : "" }}"
                                           name="agree" value="1"/>
                                    <label for="agree" class="form-check-label">
                                        I have read and agree to the
                                        <a href="{{ $termConditionPageUrl }}" target="_blank"
                                           class="{{  $errors->has('agree') ? " text-danger" : "" }}  agree">
                                            <b>Terms &amp; Conditions</b>
                                        </a>
                                    </label>


                                    @if ($errors->has('agree'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('agree') }}
                                        </div>
                                    @endif

                                </div>


                                <div class="payment float-right clearfix">
                                    <input type="button" class="btn btn-primary"
                                           data-loading-text="Loading..."
                                           id="place-order-button"
                                           value="PlaceOrder">


                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        @endif
    </div>
@endsection

@push('scripts')
<script>

$(function () {

    var CheckoutPage = {


        init:function() {
            this._me = CheckoutPage;
            this.addEvents();
            //jQuery(document).on('change', '#input-user-email',this.userInputChage);
            
        },
        addEvents: function() {

            jQuery(document).on('change', '.shipping_option_radio',this.shippingOptionChange);
            jQuery(document).on('change','.shipping_calc',this.shippingCalc);
            jQuery(document).on('change','.avored-payment-option',this.paymentOptionChange);
            jQuery(document).on('click','#place-order-button',this.placeOrderButtonClick);
            
            jQuery(document).on('click','#place-order-button',this.placeOrderButtonClick);

            jQuery("#place-order-button").bind('paymentProcessEnd', this.placeOrderButtonPaymentProcessEnd);
           
        },

        shippingOptionChange: function(e) {

            if (jQuery(this).is(':checked')) {
                var shippingTitle = jQuery(this).attr('data-title');
                var shippingCost = jQuery(this).attr('data-cost');

                jQuery('.shipping-row').removeClass('hidden');

                jQuery('.shipping-row .shipping-title').html(shippingTitle + ":");
                jQuery('.shipping-row .shipping-cost').html("$" + shippingCost);
                jQuery('.shipping-row .shipping-cost').attr('data-shipping-cost', shippingCost);


            } else {
                jQuery('.shipping-row').addClass('hidden');
            }
            CheckoutPage.calculateTotal();
        },

        shippingCalc: function(e) {

            e.preventDefault();

            if(jQuery(this).hasClass('billing') ) {

                if(!jQuery('#use_different_shipping_address').is(':checked')) {

                    jQuery('.shipping-option .shipping_option_radio').each(function (i,el) {
                        jQuery(el).trigger('shippingAddressChange');
                    });
                }
            }
        },
        paymentOptionChange: function(e) {
            e.preventDefault();
            jQuery(this).trigger('paymentOptionChange');
        },

        placeOrderButtonClick: function(e) {

            e.preventDefault();

            jQuery('.payment-radio-options input:radio').each(function (i, el) {
                if (jQuery(el).is(':checked')) {
                    //jQuery(this).prop('disabled', true);
                    jQuery(el).trigger('paymentProcessStart');
                    
                }
            });

        },

        placeOrderButtonPaymentProcessEnd: function(e) {
            e.preventDefault();

            jQuery(this).prop('disabled',false);
            jQuery('#place-order-form').submit();
        },


        calculateTotal: function() {

            var subTotal        = parseFloat(jQuery('.sub-total').attr('data-sub-total')).toFixed(2);
            var shippingCost    = parseFloat(jQuery('.shipping-cost').attr('data-shipping-cost')).toFixed(2);
            var taxAmount       = 0.00;

            var total = parseFloat(subTotal + taxAmount + shippingCost).toFixed(2);

            jQuery('.total').attr('data-total', total);
            jQuery('.total').html("$" + total);
        }


        // userInputChage:function(e) {
        //     var data = {
        //         'email': jQuery(this).val(),
        //         '_token': '{{ csrf_token()  }}'
        //     };
            
        //     $.post({
        //         url: "/check-user-exists",
        //         data: data,
        //         type: 'json',
        //         success: function (res) {
        //             console.info(res);
        //         }
        //     });
        // }
    };
   
    CheckoutPage.init();

});
</script>
@endpush
