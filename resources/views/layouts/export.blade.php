<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('shopify-app.app_name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,400;1,300;1,700&display=swap"
          rel="stylesheet">

    <link
        rel="stylesheet"
        href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
        crossorigin="anonymous">

    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link rel="stylesheet" href="{{asset('css/responsive.css')}}">

    <!-- include boostrap theme  -->
    <link rel="stylesheet" href="{{asset('alertifyjs/css/themes/default.css')}}">
    <link rel="stylesheet" href="{{asset('alertifyjs/css/alertify.css')}}">

    <!-- include alertify script -->


    @stack('styles')
</head>

<body>
<div class="overlay">
    <div class="spinner"></div>
    <div style="position: absolute;top: 59%;left: 48%; color: white">Fetching Orders...</div>
</div>


<div class="d-flex flex-column">
    <main role="main">
        <input type="hidden" id="current-shop-name" value="{{getShopName()}}">
        @yield('content')
    </main>

</div>

@if(config('shopify-app.appbridge_enabled'))
    <script
        src="https://unpkg.com/@shopify/app-bridge{{ config('shopify-app.appbridge_version') ? '@'.config('shopify-app.appbridge_version') : '' }}"></script>
    <script>
        var AppBridge = window['app-bridge'];
        var createApp = AppBridge.default;
        var app = createApp({
            apiKey: '{{ config('shopify-app.api_key') }}',
            shopOrigin: '{{ Auth::user()->name }}',
            forceRedirect: true,
        });
    </script>

    @include('shopify-app::partials.flash_messages')
@endif

@yield('scripts')
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>


<script
    src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
    crossorigin="anonymous"></script>
<script
    src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
    crossorigin="anonymous"></script>

<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="{{asset('alertifyjs/alertify.js')}}"></script>
@stack('scripts')

<script>
    // $('.overlay').show();
    // document.getElementsByClassName("overlay").style.display = "block";
</script>
</body>
</html>
