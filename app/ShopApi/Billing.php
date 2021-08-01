<?php


namespace App\ShopApi;


use Illuminate\Support\Facades\Auth;

class Billing
{
    public function createRecurringApplicationCharge()
    {
        $shop = Auth::user();
        $params = [
            "recurring_application_charge" => [
                "name" => "Orderstalker Chief Plan",
                "price" => 29.95,
                "return_url" => route('all-billing'),
                "capped_amount" => CAPPED_AMOUNT,
                "terms" => 'Charge $' . CAPPED_AMOUNT . ' on every 500+ orders',
                "trial_days" => 14,
                "test" => true
            ]
        ];

        $charge = $shop->api()->rest('POST', '/admin/api/2020-10/recurring_application_charges.json', $params);
        if ($charge['errors'] === false) {

            $confirmation_url = $charge['body']->container['recurring_application_charge']['confirmation_url'];
            return redirect()->away($confirmation_url);
        } else {
            dump('Errors');
            dd($charge);
        }
    }
}
