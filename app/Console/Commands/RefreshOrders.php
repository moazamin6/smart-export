<?php

namespace App\Console\Commands;

use App\Payment;
use App\ShopApi\Orders;
use App\Tracking;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RefreshOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is responsible for getting the orders from shopify store after 12 hours timeslot and save to trackingmore to track';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::all();
        $currentDate = Carbon::parse(Carbon::now())->format('Y-m-d');
        foreach ($users as $user) {

            $refreshDate = Carbon::parse($user->refresh_date)->format('Y-m-d');
            $diff = countDaysBetweenDates($currentDate, $refreshDate);

            if ($diff >= 30) {
                $updatedDate = Carbon::parse($user->refresh_date)->addDays(1)->format('Y-m-d');
//                $tracking = $user->trackings;
//                foreach ($tracking as $track) {
//                    Tracking::where(['user_id' => $user->id])->delete();
//                }

                $orders = Orders::all($user->id, $updatedDate, $currentDate);

                User::where(['id' => $user->id])->update(['refresh_date' => $updatedDate]);

            } else {

                $orders = Orders::all($user->id, $refreshDate, $currentDate);
            }

            Orders::fillDatabaseWithOrders($user->id, $orders);
            Orders::processSlackMessage($user->id);

            $checkoutDate = Carbon::parse($user->payment->next_payment_at)->format('Y-m-d');
            if (countDaysBetweenDates($currentDate, $checkoutDate) <= 0) {

                $ordersChargeAmount = BASIC_CHARGE_AMOUNT;
                $allOrdersCount = count($user->triggers);
                if ($allOrdersCount > 499) {

                    $ordersChargeAmount += 10; // 1000 cents = 10 USD
                } elseif ($allOrdersCount > 999) {

                    $ordersChargeAmount += 10; // 2000 cents = 20 USD
                } elseif ($allOrdersCount > 1499) {

                    $ordersChargeAmount += 10; // 3000 cents = 30 USD
                } elseif ($allOrdersCount > 1999) {

                    $ordersChargeAmount += 10; // 3000 cents = 30 USD
                }

                $charge = Payment::where(['user_id' => $user->id, 'payment_type' => PAYMENT_TYPE_RECURRING_APPLICATION_CHARGE])->first();
                paymentUsageCharge($user, $charge->payment_id, $ordersChargeAmount);
//                $customer_id = $user->payment->customer;
//                $pm_id = $user->payment->payment_method;
//                stripePayment($customer_id, $pm_id, $ordersChargeAmount);
            }
        }
        echo "Record Refreshed Successfully \n";
        return 0;
    }
}
