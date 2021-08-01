<?php namespace App\Jobs;

use App\Config;
use App\Payment;
use App\Tracking;
use App\Trigger;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Osiset\ShopifyApp\Contracts\Objects\Values\ShopDomain;
use stdClass;
use Stripe\StripeClient;

class AppUninstalledJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Shop's myshopify domain
     *
     * @var ShopDomain
     */
    public $shopDomain;

    /**
     * The webhook data
     *
     * @var object
     */
    public $data;

    /**
     * Create a new job instance.
     *
     * @param string $shopDomain The shop's myshopify domain
     * @param stdClass $data The webhook data (JSON decoded)
     *
     * @return void
     */
    public function __construct($shopDomain, $data)
    {
        $this->shopDomain = $shopDomain;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Do what you wish with the data
        // Access domain name as $this->shopDomain->toNative()
        $res = null;
        $shop = User::where(['name' => $this->shopDomain->toNative()])->first();
        Log::info('--------------Shop name: ' . $this->shopDomain->toNative());
        $res = Config::where(['user_id' => $shop->id])->delete();
        Log::info('Config Record Deleted! ' . $res);

//        try {
//            $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
//            $res = $shop->payment;
//            $del = $stripe->customers->delete($res->customer, []);
//        } catch (\Exception $e) {
//        }

        $res = Payment::where(['user_id' => $shop->id])->delete();
        Log::info('Payment Record Deleted! ' . $res);

        $res = Tracking::where(['user_id' => $shop->id])->delete();
        Log::info('Tracking Record Deleted! ' . $res);

        $res = DB::table('trigger_user')->where('user_id', $shop->id)->delete();
        Log::info('Trigger Record Deleted! ' . $res);

        $res = DB::table('users')->where('id', $shop->id)->delete();
        Log::info('User Record Deleted! ' . $res);
        Log::info('Application Uninstalled successfully!');
    }
}
