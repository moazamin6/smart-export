<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreditCardController extends Controller
{
    public function insert()
    {
        dd('');
        return view('payment/insert_card');
    }

    public function payment()
    {
        $paymentMethodId = request()->all()['payment_method'];



        return response(json_encode($paymentMethodId));
    }
}
