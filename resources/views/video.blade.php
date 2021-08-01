@extends('layouts.default')

@push('scripts')
    <style>

    </style>
@endpush


@section('content')

    <div class="video-wrapper d-flex m-5 align-content-center">

        <div class="w-50 d-flex align-items-center justify-content-center">
            <img class="img-fluid" src="{{asset('images/fancy-mobile.png')}}" alt="">
        </div>

        <div class="w-50 d-flex flex-column video box-shadow">

            <img width="110px" class="ml-5 mb-3" src="{{asset('images/logo.png')}}" alt="profile image">
{{--            <iframe class="align-self-center" src="https://www.youtube.com/embed/a0glBQXOcl4">--}}
{{--            </iframe>--}}

            <a href="{{route('install-complete')}}" target="_top" style="width: 300px"
               class="btn btn-primary align-self-center mt-5">
                Start Your {{APPLICATION_TRIAL_DAYS}} Days Trial
            </a>
        </div>

    </div>

@endsection

@push('scripts')
    <script>


    </script>
@endpush
