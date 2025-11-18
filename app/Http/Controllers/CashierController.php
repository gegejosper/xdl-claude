<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class CashierController extends Controller
{
    //
    public function index(Request $request): View{
        return view('panels.cashier.dashboard');
    }
}
