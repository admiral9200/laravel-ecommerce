@extends('mage2-ecommerce::admin.layouts.app')

@section('content')
    <div class="container">
        <h1>
            <span class="main-title-wrap">Attribute List</span>
            <a style="" href="{{ route('admin.attribute.create') }}"
               class="btn btn-primary float-right">

                Create Attribute
            </a>
        </h1>
        {!! $dataGrid->render() !!}
    </div>

@stop