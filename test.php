<?php

require 'vendor/autoload.php';
// Set your secret key. Remember to switch to your live secret key in production!
// See your keys here: https://dashboard.stripe.com/account/apikeys
\Stripe\Stripe::setApiKey('sk_test_LnuW6YiihjWg0449D4nWjOLV');

$customer = \Stripe\Customer::create();
$intent = \Stripe\SetupIntent::create([
    'customer' => $customer->id
]);


if (isset($_POST['charge'])) {

    $seti = $_POST['intent'];
    $cus = $_POST['customer'];
    $pm = $_POST['payment'];
    \Stripe\Stripe::setApiKey('sk_test_LnuW6YiihjWg0449D4nWjOLV');

    try {
        \Stripe\PaymentIntent::create([
            'amount' => 1099,
            'currency' => 'usd',
            'customer' => $cus,
            'payment_method' => $pm,
            'off_session' => true,
            'confirm' => true,
        ]);
    } catch (\Stripe\Exception\CardException $e) {
        // Error code will be authentication_required if authentication is needed
        echo 'Error code is:' . $e->getError()->code;
        $payment_intent_id = $e->getError()->payment_intent->id;
        $payment_intent = \Stripe\PaymentIntent::retrieve($payment_intent_id);
        var_dump($payment_intent_id);
        var_dump($payment_intent);
        die('EXIT');
    }
    echo $seti;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Card Test</title>
</head>

<body>


<div>
    <h3>Card Number: 4242424242424242</h3>
</div>
<div style="width: 500px; border: 1px solid #6c6c6c; padding: 20px">
    <input id="cardholder-name" type="text">
    <!-- placeholder for Elements -->
    <form id="setup-form" data-secret="<?= $intent->client_secret ?>">
        <div id="card-element"></div>
        <button id="card-button">
            Save Card
        </button>
    </form>
</div>

<form action="" method="post">
    <input type="text" placeholder="intent" name="intent">
    <input type="text" placeholder="customer id" name="customer">
    <input type="text" placeholder="payment id" name="payment">
    <input type="submit" name="charge" value="Charge">
</form>
<script src="https://js.stripe.com/v3/"></script>
<script>
    let stripe = Stripe('pk_test_UyGKJWD53wvqCtGYxcOQCxmR');

    let elements = stripe.elements();
    let cardElement = elements.create('card');
    cardElement.mount('#card-element');

    let cardholderName = document.getElementById('cardholder-name');
    let cardButton = document.getElementById('card-button');
    let clientSecret = "<?= $intent->client_secret ?>";

    cardButton.addEventListener('click', function (ev) {

        stripe.confirmCardSetup(
            clientSecret,
            {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        name: cardholderName.value,
                    },
                },
            }
        ).then(function (result) {
            console.log(result)
            if (result.error) {
                // Display error.message in your UI.
            } else {
                // The setup has succeeded. Display a success message.
            }
        });
    });
</script>
</body>

</html>
