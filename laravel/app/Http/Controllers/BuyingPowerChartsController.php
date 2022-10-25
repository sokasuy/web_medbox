<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BuyingPowerChartsController extends Controller
{
    //
    public function index()
    {
        return view('charts.buyingpower');
    }
}
