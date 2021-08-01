<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{adminAsset('admin-assets/vendors/icon_fonts/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="{{adminAsset('admin-assets/vendors/css/vendor.bundle.base.css')}}">
    <link rel="stylesheet" href="{{adminAsset('admin-assets/vendors/css/vendor.bundle.addons.css')}}">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{adminAsset('admin-assets/vendors/css/style.css')}}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{adminAsset('admin-assets/images/logo.png')}}"/>
</head>

<body>
<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
@include('admin.layouts.inc.nav')
<!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">
                <li class="nav-item nav-profile">
                    <div class="nav-link">
                        <div class="user-wrapper">
                            <div class="profile-image">
                                <img src="{{asset('images/logo.png')}}" alt="profile image">
                            </div>
                            <div class="text-wrapper">
                                <p class="profile-name">Taimur Ayyaz</p>
                                <div>
                                    <small class="designation text-muted">Administrator</small>
                                    <span class="status-indicator online"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('dashboard')}}">
                        <i class="menu-icon mdi mdi-television"></i>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- partial -->
        <div class="main-panel">

        @yield('content')

        @include('admin.layouts.inc.footer')
        <!-- partial -->
        </div>
        <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
</div>
<!-- container-scroller -->

<!-- plugins:js -->
<script src="{{adminAsset('admin-assets/vendors/js/vendor.bundle.base.js')}}"></script>
<script src="{{adminAsset('admin-assets/vendors/js/vendor.bundle.addons.js')}}"></script>
<!-- endinject -->
<!-- inject:js -->
<script src="{{adminAsset('admin-assets/vendors/js/off-canvas.js')}}"></script>
<script src="{{adminAsset('admin-assets/vendors/js/misc.js')}}"></script>
<!-- endinject -->
<!-- Custom js for this page-->
<script src="{{adminAsset('admin-assets/vendors/js/dashboard.js')}}"></script>
<!-- End custom js for this page-->

<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>


<script
    src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
    crossorigin="anonymous"></script>
{{--<script--}}
{{--    src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"--}}
{{--    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"--}}
{{--    crossorigin="anonymous"></script>--}}

<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
@stack('scripts')
</body>

</html>

