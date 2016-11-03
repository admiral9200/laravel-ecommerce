@extends('layouts.app-bootstrap')

@section('content')

        <div class="row">
            <div class="col-md-12">
                <h2>Checkout Page</h2>

                    <div class="panel panel-default">
                        <div class="panel-heading">Shipping Option</div>
                        <div class="panel-body">
                            {!! Form::open(['method'=>'post','action' => route('checkout.shipping-option.post')]) !!}

                            @foreach($shillingOptions as $shippingOption)

                                <div class="input-group {{ $errors->has($shippingOption->getIdentifier()) ? ' has-error' : '' }}">

                                    {!! Form::radio('shipping_option',$shippingOption->getTitle() . " " . $shippingOption->getAmount(),$shippingOption->getIdentifier(),['class' =>'form-control','id' => $shippingOption->getIdentifier()]) !!}
                                   

                                    @if ($errors->has($shippingOption->getIdentifier()))
                                        <span class="help-block">
                                            <strong>{{ $errors->first($shippingOption->getIdentifier()) }}</strong>
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                            <div class="input-group">
                                {!! Form::submit("Continue",['class' => 'btn btn-primary']) !!}
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>

            </div>
        </div>

@endsection