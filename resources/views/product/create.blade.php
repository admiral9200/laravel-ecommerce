@extends('layouts.polymer-app')

@section('content')

<div class="row">
    <div class="col s12">
        <h1>Edit Products</h1>
        <form method="post" id="productCreate" action="/admin/product">
            {!! csrf_field() !!}
            <paper-input autofocus autocomplete="true"  required auto-validate error-message="Title is Required" 
                         name="title"label="Title"></paper-input>
            <paper-input autofocus autocomplete="true"  required auto-validate error-message="Price is Required" 
                         name="price" label="Price"></paper-input>

            <paper-dropdown-menu name="test" label="Your favourite pastry">
                <paper-listbox class="dropdown-content">
                    <paper-item>Croissant</paper-item>
                    <paper-item>Donut</paper-item>
                    <paper-item>Financier</paper-item>
                    <paper-item>Madeleine</paper-item>
                </paper-listbox>
            </paper-dropdown-menu>
            <paper-button onclick="document.getElementById('productCreate').submit();" raised class="indigo">Create</paper-button>
        </form>
    </div>
</div>
@endsection
