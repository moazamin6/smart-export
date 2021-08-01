<?php

use App\Config;
use App\ShopApi\Orders;
use Illuminate\Support\Facades\Auth;

if (!function_exists('formatDate')) {

    function formatDate($timestamp, $format = 'd/m/y')
    {
        return 0;
        $dt = new DateTime($timestamp);
        return $dt->format($format);
    }
}
if (!function_exists('getOrderStatusClass')) {
    function getOrderStatusClass($status)
    {
        if ($status === TM_PENDING) {

            return STYLE_PENDING;
        } elseif ($status === TM_DELIVERED) {

            return STYLE_DELIVERED;
        } elseif ($status === TM_EXCEPTION) {

            return STYLE_EXCEPTION;
        } elseif ($status === TM_NOT_FOUND) {

            return STYLE_INFO_RECEIVED;
        } elseif ($status === TM_EXPIRED) {

            return STYLE_EXPIRED;
        } elseif ($status === TM_UNDELIVERED) {

            return STYLE_FAILED_ATTEMPTS;
        } elseif ($status === TM_PICKUP) {

            return STYLE_OUT_FOR_DELIVERY;
        } elseif ($status === TM_TRANSIT) {

            return STYLE_TRANSIT;
        } else {
            return '';
        }
    }
}

if (!function_exists('getOrderStatusValue')) {

    function getOrderStatusValue($status)
    {
        if ($status === TM_PENDING) {

            return STATUS_PENDING;
        } elseif ($status === TM_DELIVERED) {

            return STATUS_DELIVERED;
        } elseif ($status === TM_EXCEPTION) {

            return STATUS_EXCEPTION;
        } elseif ($status === TM_NOT_FOUND) {

            return STATUS_INFO_NOT_RECEIVED;
        } elseif ($status === TM_EXPIRED) {

            return STATUS_EXPIRED;
        } elseif ($status === TM_UNDELIVERED) {

            return STATUS_UNDELIVERED;
        } elseif ($status === TM_PICKUP) {

            return STATUS_PICKUP;
        } elseif ($status === TM_TRANSIT) {

            return STATUS_TRANSIT;
        } else {
            return '';
        }
    }
}

if (!function_exists('getOrdersAndPercentageByStatus')) {

    function getOrdersAndPercentageByStatus($orderItems, $status)
    {
        if (count($orderItems) <= 0) {
            return [
                'items' => [],
                'percentage' => 0,
            ];
        }
        $items_count = count($orderItems);
        $status_items = [];
        foreach ($orderItems as $item) {
            if ($item->status === $status) {
                $status_items[] = $item;
            }
        }
//        $status_items = array_filter($orderItems, function ($item) use ($status) {
//            return $item->status === $status;
//        });
        $status_items_count = count($status_items);

        $percentage = intval(($status_items_count / $items_count) * 100);

        return [
            'items' => $status_items,
            'percentage' => $percentage,
        ];
    }
}

if (!function_exists('getDatesBetweenOf')) {

    function getDatesBetweenOf($startDate, $endDate)
    {
        $dates = [];
        $begin = new DateTime($startDate);
        $end = new DateTime($endDate);
        $end = $end->modify('+1 day');
        $interval = new DateInterval('P1D');
        $betweenDates = new DatePeriod($begin, $interval, $end);

        foreach ($betweenDates as $date) {
            $dates[] = $date->format("Y-m-d");
        }

        return $dates;
    }
}
if (!function_exists('checkIfTriggerAlreadyAttached')) {

    function checkIfTriggerAlreadyAttached($triggerID, $userID)
    {
        $user = \App\User::find($userID);
        $exists = $user->triggers()->where(['user_id' => $userID, 'trigger_id' => $triggerID])->exists();
        if ($exists) {
            return true;
        }
        return false;
    }
}

if (!function_exists('countDaysBetweenDates')) {

    function countDaysBetweenDates($startDate, $endDate)
    {
        // Calculating the difference in timestamps
        $diff = strtotime($startDate) - strtotime($endDate);

        // 1 day = 24 hours
        // 24 * 60 * 60 = 86400 seconds
        return intval(abs(round($diff / 86400)));
    }
}

if (!function_exists('encode_id')) {

    function encode_id($ciper_text)
    {
        $encryptedCode = urlencode(base64_encode($ciper_text));
        return ($encryptedCode);
    }
}

if (!function_exists('decode_id')) {

    function decode_id($encoded_text)
    {
        $decodedCode = base64_decode(urldecode($encoded_text));
        return ($decodedCode);
    }
}

if (!function_exists('getOrderURL')) {

    function getOrderURL($user_id, $order_id)
    {
        $user = \App\User::find($user_id);
        $url = Orders::getOrderByID($user_id, $order_id)->admin_graphql_api_id;
        $domain = $user->name;
        $order_url_arr = explode('/', $url);
        $hash_id = $order_url_arr[count($order_url_arr) - 1];
        return 'https://' . $domain . '/admin/orders/' . $hash_id;
    }
}

if (!function_exists('sendSlackMessage')) {

    function sendSlackMessage($user_id, $message)
    {
        $data = array(
            'text' => $message,
        );
        $json_string = json_encode($data);
        $webhook_url = Config::getConfig($user_id, CONFIG_TYPE_SLACK_WEBHOOK);

        if ($webhook_url !== null) {

            $slack_call = curl_init();
            curl_setopt($slack_call, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($slack_call, CURLOPT_POSTFIELDS, $json_string);
            curl_setopt($slack_call, CURLOPT_CRLF, true);
            curl_setopt($slack_call, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($slack_call, CURLOPT_URL, $webhook_url->value);
            curl_setopt($slack_call, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($slack_call, CURLOPT_HTTPHEADER, array(
                'Content-type: application/json',
                'Content-Length:' . strlen($json_string)));
            $result = curl_exec($slack_call);
            curl_close($slack_call);
        }
    }
}

if (!function_exists('sendSlackMessageByHook')) {

    function sendSlackMessageByHook($hook, $message)
    {
        $data = array(
            'text' => $message,
        );

        $json_string = json_encode($data);
        if ($hook !== null) {

            $slack_call = curl_init();
            curl_setopt($slack_call, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($slack_call, CURLOPT_POSTFIELDS, $json_string);
            curl_setopt($slack_call, CURLOPT_CRLF, true);
            curl_setopt($slack_call, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($slack_call, CURLOPT_URL, $hook);
            curl_setopt($slack_call, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($slack_call, CURLOPT_HTTPHEADER, array(
                'Content-type: application/json',
                'Content-Length:' . strlen($json_string)));
            $result = curl_exec($slack_call);
            curl_close($slack_call);
            if ($result === 'ok') {
                return true;
            } else {
                return false;
            }
        }
    }
}

if (!function_exists('isUsersConfigComplete')) {

    function isUsersConfigComplete($user_id = null)
    {
        if ($user_id === null) {
            $user_id = \Illuminate\Support\Facades\Auth::user()->id;
        }

        $conf = Config::getConfig($user_id, IS_STORE_CONFIG_COMPLETE);
        if ($conf) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('stripePayment')) {

    function stripePayment($customer_id, $stripe_pm_id, $amount)
    {
        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            \Stripe\PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'usd',
                'customer' => $customer_id,
                'payment_method' => $stripe_pm_id,
                'off_session' => true,
                'confirm' => true,
            ]);
        } catch (\Stripe\Exception\CardException $e) {
            // Error code will be authentication_required if authentication is needed
            echo 'Error code is:' . $e->getError()->code;
            $payment_intent_id = $e->getError()->payment_intent->id;
            $payment_intent = \Stripe\PaymentIntent::retrieve($payment_intent_id);
            dump($payment_intent_id);
            dump($payment_intent);
            echo '</pre>';
            die('EXIT');
        }
    }
}

if (!function_exists('paymentUsageCharge')) {

    function paymentUsageCharge($shop, $recurring_charge_id, $amount)
    {
        $params = [
            "usage_charge" => [
                "description" => "Additional $" . CAPPED_AMOUNT . " Charge for 500+ orders",
                "price" => $amount,
                "test" => env('SHOPIFY_BILLING_TESTING') ? true : null
            ]
        ];
        $bill = $shop->api()->rest('POST', '/admin/api/2020-10/recurring_application_charges/' . $recurring_charge_id . '/usage_charges.json', $params);
    }
}

if (!function_exists('getShopName')) {

    function getShopName($id = null)
    {
        if ($id === null) {
            $shop = Auth::user();
        } else {
            $shop = \App\User::find($id);
        }
        return $shop->name;
    }
}

if (!function_exists('adminAsset')) {

    function adminAsset($path)
    {
        return 'https://' . APP_ADMIN_DOMAIN . '/public/' . $path;
    }
}

if (!function_exists('getAdmin')) {

    function getAdmin()
    {
        return Auth::guard('admin')->user();
    }
}

if (!function_exists('WaitForSec')) {

    function WaitForSec($sec)
    {
        $i = 1;
        $last_time = $_SERVER['REQUEST_TIME'];
        while ($i > 0) {
            $total = $_SERVER['REQUEST_TIME'] - $last_time;
            if ($total >= 2) {
                return 1;
                $i = -1;
            }
        }
    }
}


