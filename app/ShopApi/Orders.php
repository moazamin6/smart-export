<?php

namespace App\ShopApi;

use App\Tracking;
use App\User;
use Illuminate\Support\Facades\Auth;

class Orders
{
    public function __construct()
    {

    }

    public static function all($user_id, $created_at_min, $created_at_max)
    {
//        dump(date('Y-m-d', strtotime('-1 week')));
//        dd(date('Y-m-d'));
        $shop = User::find($user_id);
        $params = [
            'status' => 'pending',
            'limit' => '250',
//            'created_at_min' => $created_at_min,
//            'created_at_max' => $created_at_max,
        ];

        $orders = [];
        $is_orders = true;
        while ($is_orders) {

            $new_orders = $shop->api()->rest('GET', '/admin/api/2021-07/orders.json', $params);

            if (!$new_orders['errors']) {
                $orders = array_merge($orders, $new_orders['body']->container['orders']);
                if ($new_orders['link'] !== null) {

                    $next = $new_orders['link']->container['next'];
                    $params = ['page_info' => $next, 'limit' => '250'];

                    if ($next === null) {
                        $is_orders = false;
                    }
                } else {
                    $is_orders = false;
                }
            } else {
                $is_orders = false;
            }
        }

        return json_decode(json_encode($orders));
    }

    public static function getOrdersToExport($user_id, $order_status)
    {
        if ($order_status === 'any') {
            $params = [
                'limit' => '250',
                'status' => 'any',
            ];
        } else {

            $params = [

                'limit' => '250',
                'fulfillment_status' => $order_status,
//                'created_at_min' => '2021-08-21',
//                'created_at_max' => '2021-08-21',
            ];
        }

        $shop = User::find($user_id);
//        $params = [
//            'status' => $status,
//            'limit' => '250',
////            'created_at_min' => $created_at_min,
////            'created_at_max' => $created_at_max,
//        ];


        $orders = [];
        $is_orders = true;
        while ($is_orders) {

            $new_orders = $shop->api()->rest('GET', '/admin/api/2021-07/orders.json', $params);

            if (!$new_orders['errors']) {
                $orders = array_merge($orders, $new_orders['body']->container['orders']);
                if ($new_orders['link'] !== null) {

                    $next = $new_orders['link']->container['next'];
                    $params = ['page_info' => $next, 'limit' => '250'];

                    if ($next === null) {
                        $is_orders = false;
                    }
                } else {
                    $is_orders = false;
                }
            } else {
                $is_orders = false;
            }
        }
//        dd($orders);

        return json_decode(json_encode($orders));
    }

    public static function getOrderByID($user_id, $id)
    {

        $shop = User::find($user_id);
        $orders = $shop->api()->rest('GET', "/admin/api/orders/$id.json");
        if (!$orders['errors']) {
            $orders = $orders['body']->container['order'];
            return json_decode(json_encode($orders));
        }
        return json_decode(json_encode([]));
    }

    public static function getOrderFulfillment($order_id)
    {
        $shop = Auth::user();
        $orders = $shop->api()->rest('GET', "/admin/api/orders/$order_id/fulfillments.json");

        if (!$orders['errors']) {
            $orders = $orders['body']->fulfillments;
            return json_decode(json_encode($orders));
        }
        return json_decode(json_encode([]));
    }

    public static function getShopDetails($user_id)
    {
        $user = User::find($user_id);
        $shop = $user->api()->rest('GET', '/admin/api/2020-07/shop.json');
        if ($shop['errors'] === false) {
            return json_decode(json_encode($shop['body']->container['shop']));
        }
        return [];
    }

    public static function initializeOrders()
    {
        $user_id = Auth::user()->id;
        $created_at_min = date('Y-m-d', strtotime(INITIAL_ORDER_BACK_DAYS));
        $created_at_max = date('Y-m-d');

        $orders = self::all($user_id, $created_at_min, $created_at_max);
        self::fillDatabaseWithOrders($user_id, $orders);
    }

    public static function fillDatabaseWithOrders($user_id, $orders)
    {
        $track = new TrackingMore();

        $dt = date("Y-m-d h:i:s");
//        dump('----------------------------' . $dt . '-------------------------------');
        foreach ($orders as $order) {

            foreach ($order->fulfillments as $fulfillment) {
                if ($fulfillment->tracking_number !== null) {

                    $extraInfo = [
                        "customer_email" => $order->email,
                        "customer_name" => $order->customer->first_name . ' ' . $order->customer->last_name,
                        "order_id" => $order->id
                    ];

                    $single_tracking_data = $track->createTracking($fulfillment->tracking_number, $extraInfo);

                    if ($single_tracking_data !== []) {

                        $item_string = '';
                        foreach ($order->line_items as $line) {
                            $item_string .= $line->title . ', ';
                        }
                        $item_string = rtrim($item_string, ', ');

                        if (!isset($single_tracking_data['lastUpdateTime']) ||
                            $single_tracking_data['lastUpdateTime'] === '' ||
                            $single_tracking_data['lastUpdateTime'] === null) {
                            $lastUpdateTime = null;
                        } else {
                            $lastUpdateTime = $single_tracking_data['lastUpdateTime'];
                        }

//                        dump($single_tracking_data['carrier_code'] . "---- $fulfillment->tracking_number");

                        $tracking_data = [
                            'tracking_number' => $fulfillment->tracking_number,
                            'carrier_code' => $single_tracking_data['carrier_code'],
                            'order_number' => $order->name,
                            'status' => $single_tracking_data['status'],
                            'sub_status' => $single_tracking_data['substatus'],
                            'customer_name' => $single_tracking_data['customer_name'],
                            'country' => $order->customer->default_address->country,
                            'item_name' => $item_string,
                            'start_date' => $single_tracking_data['created_at'],
                            'order_url' => getOrderURL($user_id, $single_tracking_data['order_id']),
                            'order_date' => $order->created_at,
                            'fulfill_at' => $fulfillment->created_at,
                            'lastUpdateTime' => $lastUpdateTime
                        ];

                        $where = [
                            'tracking_number' => $fulfillment->tracking_number,
                            'carrier_code' => $single_tracking_data['carrier_code'],
                        ];

                        $user = User::find($user_id);
                        $user->trackings()->updateOrCreate($where, $tracking_data);
                    }
                }
            }
        }
    }


    public static function processSlackMessage($user_id)
    {

//        $track = new TrackingMore();
//        $orders = self::all($user_id, $created_at_min, $created_at_max);
//        self::fillDatabaseWithOrders($user_id, $orders);
        $user = User::find($user_id);

        $tracking = $user->trackings;
        $triggers = $user->triggers;

        foreach ($tracking as $tr) {
            foreach ($triggers as $tg) {

                if ($tr->status === $tg->status || $tr->sub_status === $tg->status) {

                    $today = date('y-m-d');
                    $fulfill_at = $tr->fulfill_at;
                    $days_diff = countDaysBetweenDates($today, $fulfill_at);
                    if ($days_diff) {

                        $message = "Your Order $tr->order_number | $tr->item_name | $tr->country has confronted a problem $tg->name. Click here to configure $tr->order_url";
                        sendSlackMessage($user_id, $message);
                    }
                }
            }
        }
    }
}
