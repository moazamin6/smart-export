@extends('layouts.default')

@push('scripts')
    <style>

    </style>
@endpush


@section('content')

    <div class="insert-card-wrapper d-flex m-5 box-shadow">

        <div class="d-flex flex-column justify-content-between w-40 card-text">
            <div class="left-image">
                <img class="img-fluid" src="{{asset('images/flying.png')}}" alt="">
            </div>

        </div>
        <div class="d-flex flex-column w-60 card-form p-4">
            <label for="card-holder-name">Name</label>
            <input id="card-holder-name" value="" type="text"
                   class="form-control">

            <label for="card-holder-email">Email</label>
            <input id="card-holder-email" type="text" class="form-control">
            <div>
                <p class="small float-right">We will charge $1 for card verification</p>
            </div>

            <label for="card-number" class="mt-3">Card Number</label>
            <div id="card-number" class="mb-1"></div>

            <div class="d-flex">
                <div id="card-exp"></div>

                <div id="card-cvc"></div>
            </div>

            <a href="#" id="cardButton" class="btn btn-primary align-self-center mt-4">
                Track it all
            </a>
        </div>

    </div>

@endsection

@push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>

        $(document).ready(() => {
            let config = {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }
            const stripe = Stripe('{{env('STRIPE_PUB_KEY')}}');

            const elements = stripe.elements();
            // const cardElement = elements.create('card');
            const cardNumber = elements.create('cardNumber', {
                classes: {
                    base: "form-control",
                    invalid: "error",
                }
            });
            const cardCvc = elements.create('cardCvc', {
                classes: {
                    base: "form-control",
                    invalid: "error",
                }
            });
            const cardExp = elements.create('cardExpiry', {
                classes: {
                    base: "form-control",
                    invalid: "error",
                }
            });

            // cardElement.mount('#card-element');
            cardNumber.mount('#card-number');
            cardCvc.mount('#card-cvc');
            cardExp.mount('#card-exp');

            const clientSecret = "{{$intent->client_secret}}";
            const customer_id = "{{$customer_id}}";

            $('#cardButton').click(() => {

                const email = $('#card-holder-email').val();
                const name = $('#card-holder-name').val();
                const emailReg = /\S+@\S+\.\S+/;
                if (name.trim() === '') {
                    alert('Name can not be empty');
                    return;
                }
                if (!emailReg.test(email)) {
                    alert('Enter valid Email');
                    return;
                }
                stripe.confirmCardSetup(clientSecret, {
                    payment_method: {
                        card: cardNumber,
                        billing_details: {
                            name: name
                        }
                    },
                    // setup_future_usage: 'on_session'
                }).then(function (result) {
                    if (result.error) {
                        // Show error to your customer
                        alert(result.error.message);
                        // console.log(result.error.message);
                    } else {
                        if (result.setupIntent.status === 'succeeded') {
                            // console.log(result);
                            // Show a success message to your customer
                            // There's a risk of the customer closing the window before callback execution
                            // Set up a webhook or plugin to listen for the payment_intent.succeeded event
                            // to save the card to a Customer

                            // The PaymentMethod ID can be found on result.paymentIntent.payment_method
                            axios.post('{{route('payment-info')}}', {
                                ...result.setupIntent,
                                customer_id: customer_id,
                                email: email,
                                name: name,
                            }, config)
                                .then((res) => {

                                    {{--window.location.replace('{{route('install-slack')}}');--}}
                                    window.location.replace('{{route('dashboard')}}');
                                })
                                .catch((error) => {
                                    console.log(error)
                                })
                        }
                    }
                });
            });
        });

    </script>
@endpush
