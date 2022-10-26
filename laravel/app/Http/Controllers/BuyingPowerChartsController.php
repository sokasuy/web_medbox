<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\SalesDetail;

use DB;
use DatePeriod;
use DateInterval;

use Carbon\Carbon;

use Illuminate\Http\Request;

class BuyingPowerChartsController extends Controller
{
    //
    public function index()
    {
        // DB::enableQueryLog();
        //=============================================================================================================
        //SALES
        //SELECT d.tanggal,Sum(h.total) as nominal FROM trjualh as h inner join trjuald as d on h.noinvoice=d.noinvoice and h.entiti=d.entiti WHERE d.tanggal>='2022-09-15' and d.tanggal<='2022-09-30' GROUP BY d.tanggal ORDER BY d.tanggal ASC;
        $begin = Carbon::now()->subDays(30);
        $end = Carbon::now();
        $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end); // 1-day P1D berarti Periode 1 Hari untuk lebih jelasnya lihat di https://www.php.net/manual/en/dateinterval.construct.php

        $sales = Sales::join('trjuald', function ($join) {
            $join->on('trjualh.noinvoice', '=', 'trjuald.noinvoice');
            $join->on('trjualh.entiti', '=', 'trjuald.entiti');
        })
            ->select(DB::raw("SUM(trjualh.total) as nominaljual"), 'trjualh.tanggal')
            ->where('trjualh.tanggal', '>=', $begin)->where('trjualh.tanggal', '<=', $end)->where('trjuald.faktorqty', '=', -1)
            ->groupBy('trjualh.tanggal')
            ->pluck('nominaljual', 'tanggal');

        //SALES RETURN
        $returSales = Sales::join('trjuald', function ($join) {
            $join->on('trjualh.noinvoice', '=', 'trjuald.noinvoice');
            $join->on('trjualh.entiti', '=', 'trjuald.entiti');
        })
            ->select(DB::raw("SUM(trjualh.total) as nominalretur"), 'trjualh.tanggal')
            ->where('trjualh.tanggal', '>=', $begin)->where('trjualh.tanggal', '<=', $end)->where('trjuald.faktorqty', '=', 1)
            ->groupBy('trjualh.tanggal')
            ->pluck('nominalretur', 'tanggal');
        foreach ($daterange as $date) {

            // $sales[$date] = $sales[$date] - $returSales[$date];
        }

        $labels['sales'] = $sales->keys();
        $data['sales'] = $sales->values();
        //=============================================================================================================
        return view('charts.buyingpower', compact('labels', 'data'));
    }
}
