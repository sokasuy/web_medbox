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
        $begin = Carbon::now()->subDays(30)->format('Y-m-d');
        $end = Carbon::now()->format('Y-m-d');
        $daterange = new DatePeriod(date($begin), new DateInterval('P1D'), date($end)); // 1-day P1D berarti Periode 1 Hari untuk lebih jelasnya lihat di https://www.php.net/manual/en/dateinterval.construct.php

        // $sales = Sales::join('trjuald', function ($join) {
        //     $join->on('trjualh.noinvoice', '=', 'trjuald.noinvoice');
        //     $join->on('trjualh.entiti', '=', 'trjuald.entiti');
        // })
        //     ->select(DB::raw("SUM(trjualh.total) as nominaljual"), 'trjualh.tanggal')
        //     ->where('trjualh.tanggal', '>=', $begin)->where('trjualh.tanggal', '<=', $end)
        //     ->where('trjuald.faktorqty', '=', -1)
        //     ->groupBy('trjualh.tanggal')
        //     ->pluck('nominaljual', 'tanggal');
        $sales = Sales::select(DB::raw("SUM(total) as nominaljual"), 'tanggal')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('trjuald')
                    ->whereColumn('trjualh.entiti', 'trjuald.entiti')
                    ->whereColumn('trjualh.noinvoice', 'trjuald.noinvoice')
                    ->where('trjuald.faktorqty', '=', -1);
            })
            ->where('trjualh.tanggal', '>=', $begin)->where('trjualh.tanggal', '<=', $end)
            ->groupBy('tanggal')
            ->pluck('nominaljual', 'tanggal');
        //SALES RETURN
        $returSales = Sales::select(DB::raw("SUM(total) as nominalretur"), 'tanggal')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('trjuald')
                    ->whereColumn('trjualh.entiti', 'trjuald.entiti')
                    ->whereColumn('trjualh.noinvoice', 'trjuald.noinvoice')
                    ->where('trjuald.faktorqty', '=', 1);
            })
            ->where('tanggal', '>=', $begin)->where('tanggal', '<=', $end)
            ->groupBy('tanggal')
            ->pluck('nominalretur', 'bulan');
        // dd(DB::getQueryLog());
        dd($daterange);
        foreach ($daterange as $date) {
            $sales[$date] = $sales[$date] ?? 0;
            $returSales[$date] = $returSales[$date] ?? 0;
            $netSales[$date] = $sales[$date] - $returSales[$date];
        }
        $netSales = collect((object)$netSales);


        $labels['sales'] = $netSales->keys();
        $data['sales'] = $netSales->values();
        //=============================================================================================================
        return view('charts.buyingpower', compact('labels', 'data'));
    }
}
