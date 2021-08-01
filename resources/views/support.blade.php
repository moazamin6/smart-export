@extends('layouts.default')
<link rel="stylesheet" type="text/css" href="{{asset('contact-form/main.css')}}">
@push('scripts')
    <style>

    </style>

@endpush

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="wrap-contact100">
                <form id="contact-form" class="contact100-form validate-form" method="post"
                      action="{{route('contact-us')}}">
                    <span class="contact100-form-title">
                        Contact Us
                    </span>
                    @csrf
                    <div class="wrap-input100 rs1-wrap-input100 validate-input" data-validate="Name is required">
                        <span class="label-input100">Your Name</span>
                        <input class="input100" type="text" name="name" placeholder="Enter your name">
                        <span class="focus-input100"></span>
                    </div>

                    <div class="wrap-input100 rs1-wrap-input100 validate-input"
                         data-validate="Valid email is required: ex@abc.xyz">
                        <span class="label-input100">Email</span>
                        <input class="input100" type="email" name="email" placeholder="Enter your email addess">
                        <span class="focus-input100"></span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Message is required">
                        <span class="label-input100">Message</span>
                        <textarea class="input100" name="message" placeholder="Your message here..."></textarea>
                        <span class="focus-input100"></span>
                    </div>

                    <input type="submit" class="btn btn-secondary" value="submit">
                </form>

            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>

    <script>

        $("#contact-form").validate({


            rules: {
                name: "required",
                email: "required",
                message: "required",
            },
            messages: {
                name: "Name field is required",
                email: "Email field is required",
                message: "Message field is required",
            },
            // Make sure the form is submitted to the destination defined
            // in the "action" attribute of the form when valid
            submitHandler: function (form) {
                form.submit();
            }
        });
    </script>
@endpush
