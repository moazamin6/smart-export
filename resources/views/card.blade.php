@extends('layouts.default')

@push('scripts')
    <style>

    </style>
@endpush


@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">

                    <div id="card-element">
                        <!-- Elements will create input elements here -->
                    </div>

                    <!-- We'll put the error messages in this element -->
                    <div id="card-errors" role="alert"></div>

                    <button id="submit">Pay</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        $(document).ready(() => {
            const stripe = Stripe('{{env('STRIPE_PUB_KEY')}}');
            const cardElement = document.getElementById('card-element');
            var elements = stripe.elements();
            var style = {
                base: {
                    color: "#32325d",
                }
            };
            let config = {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }
            const clientSecret = "{{$client_secret}}";
            var card = elements.create("card", {style: style});
            card.mount("#card-element");

            $('#submit').click(() => {
                stripe.confirmCardPayment(clientSecret, {
                    payment_method: {
                        card: card,
                        billing_details: {
                            name: 'Moaz Amin'
                        }
                    },
                    setup_future_usage: 'off_session'
                }).then(function (result) {
                    if (result.error) {
                        // Show error to your customer
                        console.log(result.error.message);
                    } else {
                        if (result.paymentIntent.status === 'succeeded') {
                            // Show a success message to your customer
                            // There's a risk of the customer closing the window before callback execution
                            // Set up a webhook or plugin to listen for the payment_intent.succeeded event
                            // to save the card to a Customer

                            // The PaymentMethod ID can be found on result.paymentIntent.payment_method
                            axios.post('{{route('payment')}}', {payment_method: result.paymentIntent.payment_method}, config)
                                .then((res) => {

                                    console.log(res)
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
