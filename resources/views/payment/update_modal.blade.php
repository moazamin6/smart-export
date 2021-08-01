<div class="modal fade" id="payment_update" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Card Update</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label for="card-holder-name">Name</label>
                <input id="card-holder-name" value="" type="text"
                       class="form-control">

                <label for="card-holder-email">Email</label>
                <input id="card-holder-email" type="text" class="form-control">

                <label for="card-number" class="mt-3">Card Number</label>
                <div id="card-number" class="mb-1"></div>

                <div class="d-flex">
                    <div id="card-exp"></div>

                    <div id="card-cvc"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="cardButton" class="btn btn-primary">Update</button>
            </div>
        </div>
    </div>
</div>

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

                {{--const clientSecret = "{{$intent->client_secret}}";--}}
            const customer_id = "{{$stripe_customer_id}}";

            $('#cardButton').click(() => {

                $('#cardButton').prop('disabled', 'disabled')
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
                stripe.createPaymentMethod({
                    type: 'card',
                    card: cardNumber,
                    billing_details: {
                        name: name
                    }
                    // setup_future_usage: 'on_session'
                }).then(function (result) {
                    console.log(result)
                    if (result.error) {
                        // Show error to your customer
                        alert(result.error.message);
                        // console.log(result.error.message);
                    } else {
                        // console.log(result);
                        // Show a success message to your customer
                        // There's a risk of the customer closing the window before callback execution
                        // Set up a webhook or plugin to listen for the payment_intent.succeeded event
                        // to save the card to a Customer

                        // The PaymentMethod ID can be found on result.paymentIntent.payment_method
                        axios.post('{{route('update-payment-info')}}', {
                            ...result.paymentMethod,
                            payment_method: result.paymentMethod.id,
                            customer_id: customer_id,
                            email: email,
                            name: name,
                        }, config)
                            .then((res) => {

                                if (res.data.error) {
                                    alert(res.data.message)
                                    $('#cardButton').prop('disabled', '')
                                } else {
                                    alertify.success('Card Updated Successfully');
                                    window.location.replace('{{route('dashboard')}}');
                                    {{--window.location.replace('{{route('settings')}}');--}}
                                }
                            })
                            .catch((error) => {
                                console.log(error)
                            })
                    }
                });
            });
        });

    </script>
@endpush
