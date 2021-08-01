<?php

namespace App\Http\Controllers;

use App\Config;
use App\ShopApi\Orders;
use App\Tracking;
use App\User;
use Illuminate\Contracts\View\View as ViewView;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Osiset\ShopifyApp\Actions\AuthenticateShop;
use Osiset\ShopifyApp\Actions\AuthorizeShop;
use Osiset\ShopifyApp\Objects\Values\ShopDomain;

class AuthController extends Controller
{

    /**
     * Index route which displays the login page.
     *
     * @param Request $request The HTTP request.
     *
     * @return ViewView
     */
    public function index()
    {
        return view('auth.index');

//        return View::make(
//            'shopify-app::auth.index',
//            ['shopDomain' => $request->query('shop')]
//        );
    }

    /**
     * Authenticating a shop.
     *
     * @param AuthenticateShop $authenticateShop The action for authorizing and authenticating a shop.
     *
     * @return ViewView|RedirectResponse
     */
    public function authenticate(Request $request, AuthenticateShop $authenticateShop)
    {
        // Get the shop domain
        $shopDomain = new ShopDomain($request->get('shop'));

        // if this application is already installed on underlying store
//        $shop = User::where(['name' => $request->shop])->first();
//        if ($shop) {
//
//            dd($shop);
//            // No need to install again
//            return redirect()->route('dashboard');
//        }

        // Run the action, returns [result object, result status]
        list($result, $status) = $authenticateShop($request);

        if ($status === null) {
            // Go to login, something is wrong
            return Redirect::route('login');
        } elseif ($status === false) {
            // No code, redirect to auth URL
            return $this->oauthFailure($result->url, $shopDomain);
        } else {
            // Everything's good... determine if we need to redirect back somewhere
            $return_to = Session::get('return_to');
            $user = Auth::user();
//            Orders::initializeOrders();
            User::where(['id' => Auth::user()->id])->update(['refresh_date' => date('Y-m-d', strtotime(INITIAL_ORDER_BACK_DAYS))]);
            $raw = [
                1 => ['days' => 10],
                6 => ['days' => 2],
            ];
            $user->triggers()->attach($raw);

            return redirect()->route('install-slack');
//            if ($return_to) {
//                Session::forget('return_to');
//                return Redirect::to($return_to);
//            }
//
//            // No return_to, go to home route
//            return Redirect::route('home');
        }
    }

    /**
     * Simply redirects to Shopify's Oauth screen.
     *
     * @param Request $request The request object.
     * @param AuthorizeShop $authShop The action for authenticating a shop.
     *
     * @return ViewView
     */
    public function oauth(Request $request, AuthorizeShop $authShop): ViewView
    {
        // Setup
        $shopDomain = new ShopDomain($request->get('shop'));
        $result = $authShop($shopDomain, null);

        // Redirect
        return $this->oauthFailure($result->url, $shopDomain);
    }

    /**
     * Handles when authentication is unsuccessful or new.
     *
     * @param string $authUrl The auth URl to redirect the user to get the code.
     * @param ShopDomain $shopDomain The shop's domain.
     *
     * @return ViewView
     */
    private function oauthFailure(string $authUrl, ShopDomain $shopDomain): ViewView
    {
        return View::make(
            'shopify-app::auth.fullpage_redirect',
            [
                'authUrl' => $authUrl,
                'shopDomain' => $shopDomain->toNative(),
            ]
        );
    }

    public function uninstall($type, Request $request)
    {

    }
}
