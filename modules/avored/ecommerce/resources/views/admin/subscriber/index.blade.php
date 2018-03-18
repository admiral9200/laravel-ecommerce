@extends('avored-ecommerce::admin.layouts.app')

@section('content')
    <div class="container">
        <h1>
            <span class="main-title-wrap">{{ __('avored-ecommerce::subscriber.list') }}</span>
            <a style="" href="{{ route('admin.subscriber.create') }}" class="btn btn-primary float-right">
                {{ __('avored-ecommerce::subscriber.create') }}
            </a>
        </h1>
        {!! DataGrid::render($dataGrid) !!}
    </div>

@stop
