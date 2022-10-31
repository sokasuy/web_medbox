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
        //Jumlah penjualan / jumlah transaksi
        //SELECT d.tanggal,Sum(h.total) as nominal FROM trjualh as h inner join trjuald as d on h.noinvoice=d.noinvoice and h.entiti=d.entiti WHERE d.tanggal>='2022-09-15' and d.tanggal<='2022-09-30' GROUP BY d.tanggal ORDER BY d.tanggal ASC;
        $begin = new DateTime(Carbon::now()->subDays(30)->toDateString());
        $end = new DateTime(Carbon::now()->addDays(1)->toDateString());
        $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end); // 1-day P1D berarti Periode 1 Hari untuk lebih jelasnya lihat di https://www.php.net/manual/en/dateinterval.construct.php

        $buyingPowerDaily = Sales::select(DB::raw("Round(SUM(total)/count(noinvoice),0) as buyingpower"), 'tanggal')
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
        $returBuyingPowerDaily = Sales::select(DB::raw("Round(SUM(total)/count(noinvoice),0) as retur"), 'tanggal')
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
            $buyingPowerDaily[$onlyDate] = $buyingPowerDaily[$onlyDate] ?? 0;
            $returBuyingPowerDaily[$onlyDate] = $returBuyingPowerDaily[$onlyDate] ?? 0;
            $netBuyingPowerDaily[$onlyDate] = $buyingPowerDaily[$onlyDate] - $returBuyingPowerDaily[$onlyDate];
        }
        $netBuyingPowerDaily = collect((object)$netBuyingPowerDaily);


        $labels['dailybuyingpower'] = $netBuyingPowerDaily->keys();
        $data['dailybuyingpower'] = $netBuyingPowerDaily->values();
        //=============================================================================================================

        //=============================================================================================================
        //HOURLY BUYING POWER CHART
        $begin = 6;
        $end = Carbon::now()->format('H');

        $buyingPowerHourly = Sales::select(DB::raw("Round(SUM(total)/count(noinvoice),0) as buyingpower"), DB::raw("Hour(adddate) as jam"))
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
        $returBuyingPowerHourly = Sales::select(DB::raw("Round(SUM(total)/count(noinvoice),0) as retur"), DB::raw("Hour(adddate) as jam"))
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
            $buyingPowerHourly[$begin] = $buyingPowerHourly[$begin] ?? 0;
            $returBuyingPowerHourly[$begin] = $returBuyingPowerHourly[$begin] ?? 0;
            $netBuyingPowerHourly[$begin] = $buyingPowerHourly[$begin] - $returBuyingPowerHourly[$begin];
        }
        $netBuyingPowerHourly = collect((object)$netBuyingPowerHourly);

        $labels['hourlybuyingpower'] = $netBuyingPowerHourly->keys();
        $data['hourlybuyingpower'] = $netBuyingPowerHourly->values();
        //=============================================================================================================

        //=============================================================================================================
        //DAILY SALES CHART
        //Jumlah penjualan
        //SELECT d.tanggal,Sum(h.total) as nominal FROM trjualh as h inner join trjuald as d on h.noinvoice=d.noinvoice and h.entiti=d.entiti WHERE d.tanggal>='2022-09-15' and d.tanggal<='2022-09-30' GROUP BY d.tanggal ORDER BY d.tanggal ASC;
        $begin = new DateTime(Carbon::now()->subDays(30)->toDateString());
        $end = new DateTime(Carbon::now()->addDays(1)->toDateString());
        $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end); // 1-day P1D berarti Periode 1 Hari untuk lebih jelasnya lihat di https://www.php.net/manual/en/dateinterval.construct.php

        $salesDaily = Sales::select(DB::raw("Round(SUM(total),0) as penjualan"), 'tanggal')
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
            ->pluck('penjualan', 'tanggal');
        // dd(DB::getQueryLog());

        //SALES RETURN
        $returSalesDaily = Sales::select(DB::raw("Round(SUM(total),0) as retur"), 'tanggal')
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


        $labels['dailysales'] = $netSalesDaily->keys();
        $data['dailysales'] = $netSalesDaily->values();
        //=============================================================================================================

        //=============================================================================================================
        //HOURLY SALES CHART
        $begin = 6;
        $end = Carbon::now()->format('H');

        $salesHourly = Sales::select(DB::raw("Round(SUM(total),0) as penjualan"), DB::raw("Hour(adddate) as jam"))
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
            ->pluck('penjualan', 'jam');

        //SALES RETURN
        $returSalesHourly = Sales::select(
            DB::raw("Round(SUM(total),0) as retur"),
            DB::raw("Hour(adddate) as jam")
        )
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

        $labels['hourlysales'] = $netSalesHourly->keys();
        $data['hourlysales'] = $netSalesHourly->values();
        //=============================================================================================================

        //=============================================================================================================
        //DAILY TRANSACTION CHART
        //Jumlah transaksi
        $begin = new DateTime(Carbon::now()->subDays(30)->toDateString());
        $end = new DateTime(Carbon::now()->addDays(1)->toDateString());
        $daterange = new DatePeriod(
            $begin,
            new DateInterval('P1D'),
            $end
        ); // 1-day P1D berarti Periode 1 Hari untuk lebih jelasnya lihat di https://www.php.net/manual/en/dateinterval.construct.php

        $transactionDaily = Sales::select(DB::raw("Count(noinvoice) as transaksi"), 'tanggal')
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
            ->pluck('transaksi', 'tanggal');
        // dd(DB::getQueryLog());

        //TRANSACTION RETURN
        $returTransactionDaily = Sales::select(DB::raw("Count(noinvoice) as retur"), 'tanggal')
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
            $transactionDaily[$onlyDate] = $transactionDaily[$onlyDate] ?? 0;
            $returTransactionDaily[$onlyDate] = $returTransactionDaily[$onlyDate] ?? 0;
            $netTransactionDaily[$onlyDate] = $transactionDaily[$onlyDate] - $returTransactionDaily[$onlyDate];
        }
        $netTransactionDaily = collect((object)$netTransactionDaily);


        $labels['dailytransaction'] = $netTransactionDaily->keys();
        $data['dailytransaction'] = $netTransactionDaily->values();
        //=============================================================================================================

        //=============================================================================================================
        //HOURLY TRANSACTION CHART
        $begin = 6;
        $end = Carbon::now()->format('H');

        $transactionHourly = Sales::select(DB::raw("Count(noinvoice) as transaksi"), DB::raw("Hour(adddate) as jam"))
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
            ->pluck(
                'transaksi',
                'jam'
            );

        //TRANSACTION RETURN
        $returTransactionHourly = Sales::select(
            DB::raw("Count(noinvoice) as retur"),
            DB::raw("Hour(adddate) as jam")
        )
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('trjuald')
                    ->whereColumn('trjualh.entiti', 'trjuald.entiti')
                    ->whereColumn('trjualh.noinvoice', 'trjuald.noinvoice')
                    ->where(
                        'trjuald.faktorqty',
                        '=',
                        1
                    );
            })
            ->where('tanggal', '=', Carbon::now()->toDateString())
            ->groupBy(DB::raw("Hour(adddate)"))
            ->orderBy(DB::raw("Hour(adddate)"))
            ->pluck('retur', 'jam');

        // dd(DB::getQueryLog());

        for ($begin = 6; $begin <= $end; $begin++) {
            $transactionHourly[$begin] = $transactionHourly[$begin] ?? 0;
            $returTransactionHourly[$begin] = $returTransactionHourly[$begin] ?? 0;
            $netTransactionHourly[$begin] = $transactionHourly[$begin] - $returTransactionHourly[$begin];
        }
        $netTransactionHourly = collect((object)$netTransactionHourly);

        $labels['hourlytransaction'] = $netTransactionHourly->keys();
        $data['hourlytransaction'] = $netTransactionHourly->values();
        //=============================================================================================================

        return view('charts.buyingpower', compact('labels', 'data'));
    }

    public function refreshDailyBuyingPowerChart(Request $request)
    {
        // DAILY BUYING POWER
        //09/28/2022 - 10/28/2022
        $isiFilter = $request->get('isiFilter');
        $isiFilter = explode(" - ", $isiFilter);
        $isiFilter[0] = explode("/", $isiFilter[0]);
        $isiFilter[1] = explode("/", $isiFilter[1]);
        $begin = new DateTime($isiFilter[0][2] . "-" . $isiFilter[0][0] . "-" . $isiFilter[0][1]);
        $end = new DateTime($isiFilter[1][2] . "-" . $isiFilter[1][0] . "-" . $isiFilter[1][1] + 1);

        $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end); // 1-day P1D berarti Periode 1 Hari untuk lebih jelasnya lihat di https://www.php.net/manual/en/dateinterval.construct.php

        $buyingPowerDaily = Sales::select(DB::raw("Round(SUM(total)/count(noinvoice),0) as buyingpower"), 'tanggal')
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

        //SALES RETURN
        $returBuyingPowerDaily = Sales::select(DB::raw("Round(SUM(total)/count(noinvoice),0) as retur"), 'tanggal')
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
            $buyingPowerDaily[$onlyDate] = $buyingPowerDaily[$onlyDate] ?? 0;
            $returBuyingPowerDaily[$onlyDate] = $returBuyingPowerDaily[$onlyDate] ?? 0;
            $netBuyingPowerDaily[$onlyDate] = $buyingPowerDaily[$onlyDate] - $returBuyingPowerDaily[$onlyDate];
        }
        $netBuyingPowerDaily = collect((object)$netBuyingPowerDaily);

        $ajaxData['labels'] = $netBuyingPowerDaily->keys();
        $ajaxData['data'] = $netBuyingPowerDaily->values();

        return response()->json(
            array(
                'status' => 'ok',
                'msg' => $ajaxData
            ),
            200
        );
    }

    public function refreshHourlyBuyingPowerChart(Request $request)
    {
        //HOURLY BUYING POWER CHART
        $isiFilter = $request->get('isiFilter');
        $isiFilter = explode("/", $isiFilter);
        $tanggal = new DateTime($isiFilter[2] . "-" . $isiFilter[0] . "-" . $isiFilter[1]);
        $end = new DateTime(Carbon::now()->toDateString());

        $begin = 6;
        if ($tanggal < $end) {
            $end = 23;
        } else {
            $end = Carbon::now()->format('H');
        }
        $buyingPowerHourly = Sales::select(DB::raw("Round(SUM(total)/count(noinvoice),0) as buyingpower"), DB::raw("Hour(adddate) as jam"))
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
            ->where('tanggal', '=', $tanggal)
            ->groupBy(DB::raw("Hour(adddate)"))
            ->orderBy(DB::raw("Hour(adddate)"))
            ->pluck('buyingpower', 'jam');

        //SALES RETURN
        $returBuyingPowerHourly = Sales::select(DB::raw("Round(SUM(total)/count(noinvoice),0) as retur"), DB::raw("Hour(adddate) as jam"))
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('trjuald')
                    ->whereColumn('trjualh.entiti', 'trjuald.entiti')
                    ->whereColumn('trjualh.noinvoice', 'trjuald.noinvoice')
                    ->where('trjuald.faktorqty', '=', 1);
            })
            ->where('tanggal', '=', $tanggal)
            ->groupBy(DB::raw("Hour(adddate)"))
            ->orderBy(DB::raw("Hour(adddate)"))
            ->pluck('retur', 'jam');

        for ($begin = 6; $begin <= $end; $begin++) {
            $buyingPowerHourly[$begin] = $buyingPowerHourly[$begin] ?? 0;
            $returBuyingPowerHourly[$begin] = $returBuyingPowerHourly[$begin] ?? 0;
            $netBuyingPowerHourly[$begin] = $buyingPowerHourly[$begin] - $returBuyingPowerHourly[$begin];
        }
        $netBuyingPowerHourly = collect((object)$netBuyingPowerHourly);

        $ajaxData['labels'] = $netBuyingPowerHourly->keys();
        $ajaxData['data'] = $netBuyingPowerHourly->values();

        return response()->json(
            array(
                'status' => 'ok',
                'msg' => $ajaxData
            ),
            200
        );
    }

    public function refreshDailySalesChart(Request $request)
    {
        // DAILY SALES CHART
        $isiFilter = $request->get('isiFilter');
        $isiFilter = explode(" - ", $isiFilter);
        $isiFilter[0] = explode("/", $isiFilter[0]);
        $isiFilter[1] = explode("/", $isiFilter[1]);
        $begin = new DateTime($isiFilter[0][2] . "-" . $isiFilter[0][0] . "-" . $isiFilter[0][1]);
        $end = new DateTime($isiFilter[1][2] . "-" . $isiFilter[1][0] . "-" . $isiFilter[1][1] + 1);
        // $begin = new DateTime(Carbon::now()->subDays(15)->toDateString());
        // $end = new DateTime(Carbon::now()->addDays(1)->toDateString());

        $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end); // 1-day P1D berarti Periode 1 Hari untuk lebih jelasnya lihat di https://www.php.net/manual/en/dateinterval.construct.php

        $salesDaily = Sales::select(DB::raw("Round(SUM(total),0) as penjualan"), 'tanggal')
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
            ->pluck('penjualan', 'tanggal');
        // dd(DB::getQueryLog());

        //SALES RETURN
        $returSalesDaily = Sales::select(DB::raw("Round(SUM(total),0) as retur"), 'tanggal')
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

        foreach ($daterange as $date) {
            $onlyDate = $date->Format('Y-m-d');
            $salesDaily[$onlyDate] = $salesDaily[$onlyDate] ?? 0;
            $returSalesDaily[$onlyDate] = $returSalesDaily[$onlyDate] ?? 0;
            $netSalesDaily[$onlyDate] = $salesDaily[$onlyDate] - $returSalesDaily[$onlyDate];
        }
        $netSalesDaily = collect((object)$netSalesDaily);

        $ajaxData['labels'] = $netSalesDaily->keys();
        $ajaxData['data'] = $netSalesDaily->values();

        return response()->json(
            array(
                'status' => 'ok',
                'msg' => $ajaxData
            ),
            200
        );
    }

    public function refreshHourlySalesChart(Request $request)
    {
        //HOURLY SALES CHART
        $isiFilter = $request->get('isiFilter');
        $isiFilter = explode("/", $isiFilter);
        $tanggal = new DateTime($isiFilter[2] . "-" . $isiFilter[0] . "-" . $isiFilter[1]);
        $end = new DateTime(Carbon::now()->toDateString());

        $begin = 6;
        if ($tanggal < $end) {
            $end = 23;
        } else {
            $end = Carbon::now()->format('H');
        }
        $salesHourly = Sales::select(DB::raw("Round(SUM(total),0) as penjualan"), DB::raw("Hour(adddate) as jam"))
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
            ->where('tanggal', '=', $tanggal)
            ->groupBy(DB::raw("Hour(adddate)"))
            ->orderBy(DB::raw("Hour(adddate)"))
            ->pluck('penjualan', 'jam');

        //SALES RETURN
        $returSalesHourly = Sales::select(
            DB::raw("Round(SUM(total),0) as retur"),
            DB::raw("Hour(adddate) as jam")
        )
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('trjuald')
                    ->whereColumn('trjualh.entiti', 'trjuald.entiti')
                    ->whereColumn('trjualh.noinvoice', 'trjuald.noinvoice')
                    ->where('trjuald.faktorqty', '=', 1);
            })
            ->where('tanggal', '=', $tanggal)
            ->groupBy(DB::raw("Hour(adddate)"))
            ->orderBy(DB::raw("Hour(adddate)"))
            ->pluck('retur', 'jam');

        for ($begin = 6; $begin <= $end; $begin++) {
            $salesHourly[$begin] = $salesHourly[$begin] ?? 0;
            $returSalesHourly[$begin] = $returSalesHourly[$begin] ?? 0;
            $netSalesHourly[$begin] = $salesHourly[$begin] - $returSalesHourly[$begin];
        }
        $netSalesHourly = collect((object)$netSalesHourly);

        $ajaxData['labels'] = $netSalesHourly->keys();
        $ajaxData['data'] = $netSalesHourly->values();

        return response()->json(
            array(
                'status' => 'ok',
                'msg' => $ajaxData
            ),
            200
        );
    }

    public function refreshDailyTransactionChart(Request $request)
    {
        // DAILY TRANSACTION CHART
        $isiFilter = $request->get('isiFilter');
        $isiFilter = explode(" - ", $isiFilter);
        $isiFilter[0] = explode("/", $isiFilter[0]);
        $isiFilter[1] = explode("/", $isiFilter[1]);
        $begin = new DateTime($isiFilter[0][2] . "-" . $isiFilter[0][0] . "-" . $isiFilter[0][1]);
        $end = new DateTime($isiFilter[1][2] . "-" . $isiFilter[1][0] . "-" . $isiFilter[1][1] + 1);

        $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end); // 1-day P1D berarti Periode 1 Hari untuk lebih jelasnya lihat di https://www.php.net/manual/en/dateinterval.construct.php

        $transactionDaily = Sales::select(DB::raw("Count(noinvoice) as transaksi"), 'tanggal')
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
            ->pluck('transaksi', 'tanggal');


        //TRANSACTION RETURN
        $returTransactionDaily = Sales::select(DB::raw("Count(noinvoice) as retur"), 'tanggal')
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

        foreach ($daterange as $date) {
            $onlyDate = $date->Format('Y-m-d');
            $transactionDaily[$onlyDate] = $transactionDaily[$onlyDate] ?? 0;
            $returTransactionDaily[$onlyDate] = $returTransactionDaily[$onlyDate] ?? 0;
            $netTransactionDaily[$onlyDate] = $transactionDaily[$onlyDate] - $returTransactionDaily[$onlyDate];
        }
        $netTransactionDaily = collect((object)$netTransactionDaily);

        $ajaxData['labels'] = $netTransactionDaily->keys();
        $ajaxData['data'] = $netTransactionDaily->values();

        return response()->json(
            array(
                'status' => 'ok',
                'msg' => $ajaxData
            ),
            200
        );
    }

    public function refreshHourlyTransactionChart(Request $request)
    {
        //HOURLY TRANSACTION CHART
        $isiFilter = $request->get('isiFilter');
        $isiFilter = explode("/", $isiFilter);
        $tanggal = new DateTime($isiFilter[2] . "-" . $isiFilter[0] . "-" . $isiFilter[1]);
        $end = new DateTime(Carbon::now()->toDateString());

        $begin = 6;
        if ($tanggal < $end) {
            $end = 23;
        } else {
            $end = Carbon::now()->format('H');
        }
        $transactionHourly = Sales::select(DB::raw("Count(noinvoice) as transaksi"), DB::raw("Hour(adddate) as jam"))
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
            ->where('tanggal', '=', $tanggal)
            ->groupBy(DB::raw("Hour(adddate)"))
            ->orderBy(DB::raw("Hour(adddate)"))
            ->pluck(
                'transaksi',
                'jam'
            );

        //TRANSACTION RETURN
        $returTransactionHourly = Sales::select(
            DB::raw("Count(noinvoice) as retur"),
            DB::raw("Hour(adddate) as jam")
        )
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('trjuald')
                    ->whereColumn('trjualh.entiti', 'trjuald.entiti')
                    ->whereColumn('trjualh.noinvoice', 'trjuald.noinvoice')
                    ->where(
                        'trjuald.faktorqty',
                        '=',
                        1
                    );
            })
            ->where('tanggal', '=', $tanggal)
            ->groupBy(DB::raw("Hour(adddate)"))
            ->orderBy(DB::raw("Hour(adddate)"))
            ->pluck('retur', 'jam');

        // dd(DB::getQueryLog());

        for ($begin = 6; $begin <= $end; $begin++) {
            $transactionHourly[$begin] = $transactionHourly[$begin] ?? 0;
            $returTransactionHourly[$begin] = $returTransactionHourly[$begin] ?? 0;
            $netTransactionHourly[$begin] = $transactionHourly[$begin] - $returTransactionHourly[$begin];
        }
        $netTransactionHourly = collect((object)$netTransactionHourly);

        $ajaxData['labels'] = $netTransactionHourly->keys();
        $ajaxData['data'] = $netTransactionHourly->values();

        return response()->json(
            array(
                'status' => 'ok',
                'msg' => $ajaxData
            ),
            200
        );
    }
}
