@extends('avored-ecommerce::admin.layouts.app')
@section('content')
    <div class="container">
        <div class="h1">Orders</div>
        {!! DataGrid::render($dataGrid) !!}
    </div>
@stop
