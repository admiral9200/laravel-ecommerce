<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Mage2 Ecommerce') }}</title>

    <link href="{{ asset('vendor/mage2-admin/css/app.css') }}" rel="stylesheet">


    <!-- Scripts -->
    <script>
        window.Laravel = <?php
        echo json_encode([
                'csrfToken' => csrf_token(),
        ]);
        ?>
    </script>
    @stack('styles')
</head>
<body>

@include("mage2-ecommerce::admin.layouts.nav")


<aside class="" style="position: absolute; left: 0px;width: 200px;">
    @include("mage2-ecommerce::admin.layouts.left-nav")
</aside>
<div class="main-content p-3" style="margin-left: 200px; ">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @if(session()->has('notificationText'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>

                        <strong>Success!</strong> {{ session()->get('notificationText') }}

                    </div>
                @endif
            </div>
        </div>
        @yield('content')
    </div>

    @include('mage2-ecommerce::admin.layouts.footer')
</div>



<script src="{{ asset('vendor/mage2-admin/js/app.js') }}"></script>

@stack('scripts')

</body>
</html>
