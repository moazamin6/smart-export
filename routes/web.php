<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CreditCardController;
use App\Http\Controllers\InstallController;
use App\Mail\ContactUsMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/mail', function () {
    $data = [
        'name' => 'Moaz Amin',
        'email' => 'nadeem@exoah.com',
        'message' => 'This is test message',
    ];
    $data = json_decode(json_encode($data));
    Mail::to(env('MAIL_USERNAME'))->send(new ContactUsMail($data));

//    $to_name = 'Moaz Amin';
//    $to_email = 'orderstalker@gmail.com';
//    $data = array('name' => "Sam Jose", "body" => "Test mail");
//    Mail::send('emails', $data, function ($message) use ($to_name, $to_email) {
//        $message->to($to_email, $to_name)->subject('Artisans Web Testing Mail');
////        $message->from('kamran@gmail.com', 'Artisans Web');
//    });

    dd('Email send successfully');
});
Route::get('/test', function () {

    dump('This is test');
});
Route::post('/webhook/uninstall', [AuthController::class, 'uninstall'])
    ->middleware('auth.webhook')
    ->name('webhook');

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::match(['get', 'post'], '/authenticate', [AuthController::class, 'authenticate'])->name('authenticate');

Route::group(['middleware' => ['auth.shopify']], function () {

    Route::get('/billing-test', [AppController::class, 'billingTest'])->name('billing-test');
    Route::get('/all-billing', [AppController::class, 'allBilling'])->name('all-billing');

    Route::get('/', function () {

        return redirect()->route('export-form');
    });

//    Route::get('/', [AppController::class, 'dashboard'])
//        ->name('dashboard');

    Route::get('/filter/{startDate}/{endDate}', [AppController::class, 'dashboard'])
        ->name('date-filter');

    Route::get('/home', [AppController::class, 'dashboard'])
        ->name('home');

    Route::get('/export/form', [AppController::class, 'exportForm'])
        ->name('export-form');

    Route::post('/export/form/excel', [AppController::class, 'exportFormToExcel'])
        ->name('export-form-excel');

    // Triggers Route
    Route::get('/triggers', [AppController::class, 'triggers'])
        ->name('triggers');

    Route::post('/trigger/add', [AppController::class, 'store'])
        ->name('add-trigger');

    Route::delete('/trigger/remove/{id}', [AppController::class, 'destroy'])
        ->name('remove-trigger');

    Route::put('/trigger/update/{id}', [AppController::class, 'update'])
        ->name('update-trigger');


    // Settings Route

    Route::get('/card/insert', [CreditCardController::class, 'insert'])
        ->name('card-insert');

    Route::post('/payment', [CreditCardController::class, 'payment'])
        ->name('payment');


    // Install Routes
    Route::get('/install/payment', [InstallController::class, 'showCheckoutForm'])
        ->name('install-payment');

    Route::post('/install/payment_info', [InstallController::class, 'savePaymentInfo'])
        ->name('payment-info');

    Route::post('/install/update_payment_info', [AppController::class, 'updatePaymentInfo'])
        ->name('update-payment-info');

    Route::get('/install/slack', [InstallController::class, 'showSlackForm'])
        ->name('install-slack');

    Route::get('/install/video', [InstallController::class, 'showVideoPage'])
        ->name('video');

    Route::get('/install/complete', [InstallController::class, 'complete'])
        ->name('install-complete');

    Route::get('/settings', [AppController::class, 'settings'])
        ->name('settings');

    Route::post('/setting/save', [AppController::class, 'settingSave'])
        ->name('setting-save');

    Route::post('/config/save', [AppController::class, 'configSave'])
        ->name('config-save');

    Route::get('/fetch-data', [AppController::class, 'fetchData'])
        ->name('fetch-data');

    Route::get('/support', [AppController::class, 'support'])
        ->name('support');

    Route::post('/support/contact-us', [AppController::class, 'contactUs'])
        ->name('contact-us');

    Route::get('/app/trial', [AppController::class, 'trialAccepted'])->name('appTrial');


    Route::get('/trigger/test', [AppController::class, 'testTriggers'])->name('testTriggers');
});

//Route::group(array('domain' => APP_DOMAIN, 'middleware' => ['auth.shopify']), function () {});

Route::group(array('domain' => APP_ADMIN_DOMAIN, 'prefix' => 'admin'), function () {

    Route::get('/', [AdminController::class, 'index'])->name('admin-dashboard');
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin-login-form');
    Route::post('/login', [AdminAuthController::class, 'adminAuthenticate'])->name('admin-login');
    Route::post('/logout', [AdminAuthController::class, 'adminLogout'])->name('admin-logout');


    Route::get('/connected/stores', [AdminController::class, 'getLatestConnectedStoresCount'])->name('connected-stores');
});

