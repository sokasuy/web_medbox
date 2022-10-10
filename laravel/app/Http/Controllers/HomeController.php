<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Sales;
use App\Models\StokBarang;
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
        $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        // $beli = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $purchase = Purchase::select(DB::raw("SUM(subtotal) as totalbeli"), DB::raw("MONTHNAME(tanggal) as bulan"))
            ->whereYear('tanggal', '=', Carbon::now()->format('Y'))
            ->groupBy(DB::raw("MONTHNAME(tanggal)"))
            ->pluck('totalbeli', 'bulan');

        // $beli = $purchase;
        // unset($beli);
        foreach ($months as $bulan) {
            if (($purchase[$bulan]) ?? null) {
                $beli[$bulan] = $purchase[$bulan];
            } else {
                $beli[$bulan] = 0;
            }
        }
        // dd((object)$beli);
        $beli = collect((object)$beli);
        $labels['purchase'] = $beli->keys();
        $data['purchase'] = $beli->values();

        // $record = Purchase::select(DB::raw("SUM(subtotal) as totalbeli"), DB::raw("MONTHNAME(tanggal) as bulan"))
        //     ->where('tanggal', '>=', Carbon::now()->subMonth(12))
        //     ->groupBy(DB::raw("MONTHNAME(tanggal)"))
        //     ->get();

        // $dataOri = [];

        // foreach ($record as $row) {
        //     $dataOri['label'][] = $row->bulan;
        //     $dataOri['data'][] = (int) $row->totalbeli;
        // }
        // dd($dataOri);

        // $data = [];

        // foreach ($months as $bulan) {
        //     foreach ($dataOri['data'] as $ori) {
        //         dd($ori);
        //         if ($ori === $bulan) {
        //             $data['label'][] = $bulan;
        //             $data['data'][] = $ori['data'];
        //         } else {
        //             $data['label'][] = $bulan;
        //             $data['data'][] = 0;
        //         }
        //     }
        // }
        // dd($data);

        // $data = [];
        // foreach ($months as $bulan) {
        //     foreach ($purchase as $beli) {
        //         if (($purchase[$bulan]) ?? null) {
        //             $data["labelBeli"][] = $bulan;
        //             $data["dataBeli"][] = $purchase->totalbeli;
        //         } else {
        //             $data["labelBeli"][] = $bulan;
        //             $data["dataBeli"][] = 0;
        //         }
        //         $data['label'][] = $row->day_name;
        //         $data['data'][] = (int) $row->count;
        //     }
        // }
        // $data['chart_beli'] = json_encode($data);

        // dd($data);
        // $object = json_encode($beli);
        // $beli = json_decode(json_encode($object));
        // dd($beli);

        $sales = Sales::select(DB::raw("SUM(total) as totaljual"), DB::raw("MONTHNAME(tanggal) as bulan"))
            ->whereYear('tanggal', '=', Carbon::now()->format('Y'))
            ->groupBy(DB::raw("MONTHNAME(tanggal)"))
            ->pluck('totaljual', 'bulan');
        // ->get();

        foreach ($months as $bulan) {
            if (($sales[$bulan]) ?? null) {
                $jual[$bulan] = $sales[$bulan];
            } else {
                $jual[$bulan] = 0;
            }
        }
        $jual = collect((object)$jual);
        // dd($jual);
        $labels['sales'] = $jual->keys();
        $data['sales'] = $jual->values();
        // foreach ($months as $bulan) {
        //     if (($purchase[$bulan]) ?? null) {
        //         $data["labelJual"][] = $bulan;
        //         $data["dataJual"][] = $sales->totaljual;
        //     } else {
        //         $data["labelJual"][] = $bulan;
        //         $data["dataJual"][] = 0;
        //     }
        // }
        // $data['chart_jual'] = json_encode($data);

        $profitloss = StokBarang::select(DB::raw("SUM((qty*harga)-(qty*hpp)) as profit_loss"), DB::raw("MONTHNAME(tanggal) as bulan"))
            ->LeftJoin()
            ->whereYear('tanggal', '=', Carbon::now()->format('Y'))
            ->groupBy(DB::raw("MONTHNAME(tanggal)"))
            ->pluck('totaljual', 'bulan');

        // //return view('home', compact('labelsPurchase', 'dataPurchase', 'labelsSales', 'dataSales'));
        // // return view('home', $data);
        return view('home', compact('labels', 'data'));
    }
}
