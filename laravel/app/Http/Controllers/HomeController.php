<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use DB;
use Carbon\Carbon;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $purchase = Purchase::select(DB::raw("SUM(subtotal) as totalbeli"), DB::raw("month(tanggal) as bulan"))
            ->where('tanggal', '>=', Carbon::now()->subMonth(12))
            ->groupBy(DB::raw("Month(tanggal)"))
            ->pluck('totalbeli', 'bulan');

        $labels = $purchase->keys();
        $data = $purchase->values();

        return view('home', compact('labels', 'data'));
    }
}
