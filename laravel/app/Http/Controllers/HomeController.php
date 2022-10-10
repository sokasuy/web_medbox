<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Sales;
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
        $purchase = Purchase::select(DB::raw("SUM(subtotal) as totalbeli"), DB::raw("MONTHNAME(tanggal) as bulan"))
            ->where('tanggal', '>=', Carbon::now()->subMonth(12))
            ->groupBy(DB::raw("MONTHNAME(tanggal)"))
            ->pluck('totalbeli', 'bulan');

        $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        $beli = $purchase;
        foreach ($months as $bulan) {
            if (($purchase[$bulan]) ?? null) {
                $beli[$bulan] = $purchase[$bulan];
            } else {
                $beli[$bulan] = 0;
            }
        }

        $labels['purchase'] = $beli->keys();
        // dd($labels['purchase']);
        $data['purchase'] = $beli->values();
        // dd($data['purchase']);

        $sales = Sales::select(DB::raw("SUM(total) as totaljual"), DB::raw("MONTHNAME(tanggal) as bulan"))
            ->where('tanggal', '>=', Carbon::now()->subMonth(12))
            ->groupBy(DB::raw("MONTHNAME(tanggal)"))
            ->pluck('totaljual', 'bulan');

        $labels['sales'] = $sales->keys();
        $data['sales'] = $sales->values();

        // dd($sales['September']);

        //return view('home', compact('labelsPurchase', 'dataPurchase', 'labelsSales', 'dataSales'));
        return view('home', compact('labels', 'data'));
    }
}
