<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\SalesDetail;

use DB;
use DatePeriod;
use DateInterval;
use DateTime;

use Carbon\Carbon;

use Illuminate\Http\Request;

class BuyingPowerChartsController extends Controller
{
    //
    public function index()
    {
        // DB::enableQueryLog();
        //=============================================================================================================
        //DAILY BUYING POWER CHART
        //SELECT d.tanggal,Sum(h.total) as nominal FROM trjualh as h inner join trjuald as d on h.noinvoice=d.noinvoice and h.entiti=d.entiti WHERE d.tanggal>='2022-09-15' and d.tanggal<='2022-09-30' GROUP BY d.tanggal ORDER BY d.tanggal ASC;
        $begin = new DateTime(Carbon::now()->subDays(30)->toDateString());
        $end = new DateTime(Carbon::now()->addDays(1)->toDateString());
        $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end); // 1-day P1D berarti Periode 1 Hari untuk lebih jelasnya lihat di https://www.php.net/manual/en/dateinterval.construct.php

        $salesDaily = Sales::select(DB::raw("Round(SUM(total)/count(noinvoice),0) as buyingpower"), 'tanggal')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('trjuald')
                    ->whereColumn('trjualh.entiti', 'trjuald.entiti')
                    ->whereColumn('trjualh.noinvoice', 'trjuald.noinvoice')
                    ->where('trjuald.faktorqty', '=', -1);
            })
            ->where('tanggal', '>=', $begin)->where('tanggal', '<=', $end)
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->pluck('buyingpower', 'tanggal');
        // dd(DB::getQueryLog());

        //SALES RETURN
        $returSalesDaily = Sales::select(DB::raw("Round(SUM(total)/count(noinvoice),0) as retur"), 'tanggal')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('trjuald')
                    ->whereColumn('trjualh.entiti', 'trjuald.entiti')
                    ->whereColumn('trjualh.noinvoice', 'trjuald.noinvoice')
                    ->where('trjuald.faktorqty', '=', 1);
            })
            ->where('tanggal', '>=', $begin)->where('tanggal', '<=', $end)
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->pluck('retur', 'tanggal');

        // dd(DB::getQueryLog());

        foreach ($daterange as $date) {
            $onlyDate = $date->Format('Y-m-d');
            $salesDaily[$onlyDate] = $salesDaily[$onlyDate] ?? 0;
            $returSalesDaily[$onlyDate] = $returSalesDaily[$onlyDate] ?? 0;
            $netSalesDaily[$onlyDate] = $salesDaily[$onlyDate] - $returSalesDaily[$onlyDate];
        }
        $netSalesDaily = collect((object)$netSalesDaily);


        $labels['dailybuyingpower'] = $netSalesDaily->keys();
        $data['dailybuyingpower'] = $netSalesDaily->values();
        //=============================================================================================================

        //=============================================================================================================
        //HOURLY BUYING POWER CHART
        $begin = 6;
        $end = Carbon::now()->format('H');

        $salesHourly = Sales::select(DB::raw("Round(SUM(total)/count(noinvoice),0) as buyingpower"), DB::raw("Hour(adddate) as jam"))
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('trjuald')
                    ->whereColumn(
                        'trjualh.entiti',
                        'trjuald.entiti'
                    )
                    ->whereColumn('trjualh.noinvoice', 'trjuald.noinvoice')
                    ->where('trjuald.faktorqty', '=', -1);
            })
            ->where('tanggal', '=', Carbon::now()->toDateString())
            ->groupBy(DB::raw("Hour(adddate)"))
            ->orderBy(DB::raw("Hour(adddate)"))
            ->pluck('buyingpower', 'jam');

        //SALES RETURN
        $returSalesHourly = Sales::select(DB::raw("Round(SUM(total)/count(noinvoice),0) as retur"), DB::raw("Hour(adddate) as jam"))
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('trjuald')
                    ->whereColumn('trjualh.entiti', 'trjuald.entiti')
                    ->whereColumn('trjualh.noinvoice', 'trjuald.noinvoice')
                    ->where('trjuald.faktorqty', '=', 1);
            })
            ->where('tanggal', '=', Carbon::now()->toDateString())
            ->groupBy(DB::raw("Hour(adddate)"))
            ->orderBy(DB::raw("Hour(adddate)"))
            ->pluck('retur', 'jam');

        // dd(DB::getQueryLog());

        for ($begin = 6; $begin <= $end; $begin++) {
            $salesHourly[$begin] = $salesHourly[$begin] ?? 0;
            $returSalesHourly[$begin] = $returSalesHourly[$begin] ?? 0;
            $netSalesHourly[$begin] = $salesHourly[$begin] - $returSalesHourly[$begin];
        }
        $netSalesHourly = collect((object)$netSalesHourly);

        $labels['hourlybuyingpower'] = $netSalesHourly->keys();
        $data['hourlybuyingpower'] = $netSalesHourly->values();
        //=============================================================================================================
        return view('charts.buyingpower', compact('labels', 'data'));
    }
}
