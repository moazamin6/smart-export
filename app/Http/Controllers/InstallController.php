<?php

namespace App\Http\Controllers;

use App\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\SetupIntent;
use Stripe\Stripe;
use Stripe\StripeClient;

class InstallController extends Controller
{
    public function showCheckoutForm()
    {
        $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $customer = $stripe->customers->create();
        $intent = SetupIntent::create([
            'customer' => $customer->id,
        ]);

        return view('payment.insert_card')
            ->with('customer_id', $customer->id)
            ->with('intent', $intent);
    }

    public function savePaymentInfo()
    {
        $res = request()->all();
        $stripe_customer_id = $res['customer_id'];
        $stripe_pm_id = $res['payment_method'];
        $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
        $customer = $stripe->customers->update(
            $res['customer_id'],
            ['email' => $res['email'],
                'description' => APP_INSTALL_PAYMENT_DESCRIPTION]
        );

        $last_four = $stripe->paymentMethods->all(['customer' => $res['customer_id'], 'type' => 'card',])->data[0]['card']['last4'];

        $next_payment_at = date('Y-m-d', strtotime('+60 day'));
        $amount = '100';
        stripePayment($stripe_customer_id, $stripe_pm_id, $amount);
        $data = [
            'name' => $res['name'],
            'customer' => $stripe_customer_id,
            'amount' => $amount,
            'payment_method' => $stripe_pm_id,
            'next_payment_at' => $next_payment_at,
            'last_four' => $last_four,
        ];

        $user = Auth::user();
        $user->payment()->updateOrCreate(['user_id' => Auth::user()->id], $data);

        return response(json_encode($data));
    }

    public function showSlackForm()
    {
        return view('slack_form');
    }

    public function showVideoPage()
    {

        return view('video');
    }

    public function complete()
    {
        Config::saveConfig(IS_STORE_CONFIG_COMPLETE, 'completed');
//        Config::saveConfig(CONFIG_TYPE_CARD_ATTACHED, 'attached');

        $shop = Auth::user();
        $params = [
            "recurring_application_charge" => [

                "name" => "Orderstalker Chief Plan",
                "price" => BASIC_CHARGE_AMOUNT,
                "return_url" => route('appTrial'),
                "capped_amount" => CAPPED_AMOUNT,
                "terms" => 'Charge $10 on every 500+ orders',
                "trial_days" => APPLICATION_TRIAL_DAYS,
                "test" => env('SHOPIFY_BILLING_TESTING') ? true : null
            ]
        ];

        $charge = $shop->api()->rest('POST', '/admin/api/2020-10/recurring_application_charges.json', $params);

        if ($charge['errors'] === false) {

            $confirmation_url = $charge['body']->container['recurring_application_charge']['confirmation_url'];
            return redirect()->away($confirmation_url);
        } else {
            dump('Errors During Creating Recurring Charge');
            dd($charge);
        }

//        return redirect()->route('dashboard');
    }
}
