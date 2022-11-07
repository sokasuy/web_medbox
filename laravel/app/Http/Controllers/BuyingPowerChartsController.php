<?php

namespace App\Http\Controllers;

use App\Models\Sales;

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
        $begin = new DateTime(Carbon::now()->subDays(30)->toDateString());
        $end = new DateTime(Carbon::now()->addDays(1)->toDateString());
        $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end); // 1-day P1D berarti Periode 1 Hari untuk lebih jelasnya lihat di https://www.php.net/manual/en/dateinterval.construct.php

        $netBuyingPowerDaily = Sales::getDailyBuyingPowerChart($begin, $end, $daterange);

        $labels['dailybuyingpower'] = $netBuyingPowerDaily->keys();
        $data['dailybuyingpower'] = $netBuyingPowerDaily->values();
        //=============================================================================================================

        //=============================================================================================================
        //HOURLY BUYING POWER CHART
        $begin = 6;
        $end = Carbon::now()->format('H');

        $netBuyingPowerHourly = Sales::getHourlyBuyingPowerChart($begin, $end, Carbon::now()->toDateString());

        $labels['hourlybuyingpower'] = $netBuyingPowerHourly->keys();
        $data['hourlybuyingpower'] = $netBuyingPowerHourly->values();
        //=============================================================================================================

        //=============================================================================================================
        //DAILY SALES CHART
        //Jumlah penjualan
        $begin = new DateTime(Carbon::now()->subDays(30)->toDateString());
        $end = new DateTime(Carbon::now()->addDays(1)->toDateString());
        $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end); // 1-day P1D berarti Periode 1 Hari untuk lebih jelasnya lihat di https://www.php.net/manual/en/dateinterval.construct.php

        $netSalesDaily = Sales::getDailySalesChart($begin, $end, $daterange);
        $labels['dailysales'] = $netSalesDaily->keys();
        $data['dailysales'] = $netSalesDaily->values();
        //=============================================================================================================

        //=============================================================================================================
        //HOURLY SALES CHART
        $begin = 6;
        $end = Carbon::now()->format('H');

        $netSalesHourly = Sales::getHourlySalesChart($begin, $end, Carbon::now()->toDateString());

        $labels['hourlysales'] = $netSalesHourly->keys();
        $data['hourlysales'] = $netSalesHourly->values();
        //=============================================================================================================

        //=============================================================================================================
        //DAILY TRANSACTION CHART
        //Jumlah transaksi
        $begin = new DateTime(Carbon::now()->subDays(30)->toDateString());
        $end = new DateTime(Carbon::now()->addDays(1)->toDateString());
        $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end); // 1-day P1D berarti Periode 1 Hari untuk lebih jelasnya lihat di https://www.php.net/manual/en/dateinterval.construct.php

        $netTransactionDaily = Sales::getDailyTransactionChart($begin, $end, $daterange);

        $labels['dailytransaction'] = $netTransactionDaily->keys();
        $data['dailytransaction'] = $netTransactionDaily->values();
        //=============================================================================================================

        //=============================================================================================================
        //HOURLY TRANSACTION CHART
        $begin = 6;
        $end = Carbon::now()->format('H');

        $netTransactionHourly = Sales::getHourlyTransactionChart($begin, $end, Carbon::now()->toDateString());

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
        $end = new DateTime($isiFilter[1][2] . "-" . $isiFilter[1][0] . "-" . $isiFilter[1][1]);
        $end = $end->modify('+1 day');
        $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end); // 1-day P1D berarti Periode 1 Hari untuk lebih jelasnya lihat di https://www.php.net/manual/en/dateinterval.construct.php

        $netBuyingPowerDaily = Sales::getDailyBuyingPowerChart($begin, $end, $daterange);

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

        $netBuyingPowerHourly = Sales::getHourlyBuyingPowerChart($begin, $end, $tanggal);

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
        $end = new DateTime($isiFilter[1][2] . "-" . $isiFilter[1][0] . "-" . $isiFilter[1][1]);
        $end = $end->modify('+1 day');

        $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end); // 1-day P1D berarti Periode 1 Hari untuk lebih jelasnya lihat di https://www.php.net/manual/en/dateinterval.construct.php

        $netSalesDaily = Sales::getDailySalesChart($begin, $end, $daterange);

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

        $netSalesHourly = Sales::getHourlySalesChart($begin, $end, $tanggal);

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
        $end = new DateTime($isiFilter[1][2] . "-" . $isiFilter[1][0] . "-" . $isiFilter[1][1]);
        $end = $end->modify('+1 day');

        $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end); // 1-day P1D berarti Periode 1 Hari untuk lebih jelasnya lihat di https://www.php.net/manual/en/dateinterval.construct.php

        $netTransactionDaily = Sales::getDailyTransactionChart($begin, $end, $daterange);

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

        $netTransactionHourly = Sales::getHourlyTransactionChart($begin, $end, $tanggal);

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
