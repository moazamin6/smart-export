<?php

namespace App\Http\Controllers;

use App\Tracking;
use App\User;

class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $connectedStores = User::all();
        $trackings = Tracking::all();

        return view('admin.dashboard')
            ->with('connectedStores', $connectedStores)
            ->with('trackings', $trackings);
    }

    public function getLatestConnectedStoresCount()
    {
        return json_encode(['stores' => User::all()]);
    }
}
