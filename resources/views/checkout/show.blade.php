@extends('layouts.app')

@section('content')
  <checkout-page inline-template>
    <div>
    <h1>{{ __('Checkout Page') }}</h1>
    <a-form :form="form" @submit="handleSubmit" method="post" action="#">
      @csrf          
      <a-row :gutter="15">
        <a-col :span="12">
         
              @include('checkout.cards.personal')   
          
              @include('checkout.cards.shipping-address')
              @include('checkout.cards.billing-address')
        
        </a-col>
        <a-col :span="12">
              @include('checkout.cards.shipping-option')   
              @include('checkout.cards.payment-option')   
              @include('checkout.cards.cart-items')   
              <a-form-item class="mt-1">
                <a-button
                    type="primary"
                    :loading="submitStatus"
                    html-type="submit">
                    PlaceOrder
                </a-button>
            </a-form-item>
           
        </a-col>
      </a-row>

      
    </a-form>
    </div>
  </checkout-page>
@endsection
