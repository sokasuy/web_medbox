<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use DB;
use DateTime;

class Sales extends Model
{
    use HasFactory;
    protected $table = 'trjualh';
    protected $primaryKey = ['entiti', 'noinvoice'];
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;
    const CREATED_AT = 'adddate';
    const UPDATED_AT = 'editdate';

    // DB::enableQueryLog();

    public static function getPenjualanByPeriode($kriteria, $isiFilter)
    {
        if ($kriteria == "hari_ini") {
            $data = self::on()->select('trjualh.entiti', 'trjualh.noinvoice', 'trjualh.tanggal', 'trjualh.pembayaran', 'trjuald.sku', 'trjuald.namabarang', 'trjuald.qty', 'trjuald.satuan', 'trjuald.harga', 'trjuald.jumlah', 'trjuald.statusbarang', 'trjualh.adddate', 'trjualh.editdate')
                ->join('trjuald', function ($join) {
                    $join->on('trjuald.entiti', '=', 'trjualh.entiti');
                    $join->on('trjuald.noinvoice', '=', 'trjualh.noinvoice');
                })->where('trjualh.tanggal', '=', $isiFilter)
                ->where('trjuald.faktorqty', '=', -1)
                ->orderBy('trjualh.adddate')
                ->get();
        } else if ($kriteria == "3_hari" || $kriteria == "7_hari" || $kriteria == "14_hari") {
            $data = self::on()->select('trjualh.entiti', 'trjualh.noinvoice', 'trjualh.tanggal', 'trjualh.pembayaran', 'trjuald.sku', 'trjuald.namabarang', 'trjuald.qty', 'trjuald.satuan', 'trjuald.harga', 'trjuald.jumlah', 'trjuald.statusbarang', 'trjualh.adddate', 'trjualh.editdate')
                ->join('trjuald', function ($join) {
                    $join->on('trjuald.entiti', '=', 'trjualh.entiti');
                    $join->on('trjuald.noinvoice', '=', 'trjualh.noinvoice');
                })->where('trjualh.tanggal', '>=', $isiFilter)
                ->where('trjuald.faktorqty', '=', -1)
                ->orderBy('trjualh.adddate')
                ->get();
        } else if ($kriteria == "bulan_berjalan") {
            $data = self::on()->select('trjualh.entiti', 'trjualh.noinvoice', 'trjualh.tanggal', 'trjualh.pembayaran', 'trjuald.sku', 'trjuald.namabarang', 'trjuald.qty', 'trjuald.satuan', 'trjuald.harga', 'trjuald.jumlah', 'trjuald.statusbarang', 'trjualh.adddate', 'trjualh.editdate')
                ->join('trjuald', function ($join) {
                    $join->on('trjuald.entiti', '=', 'trjualh.entiti');
                    $join->on('trjuald.noinvoice', '=', 'trjualh.noinvoice');
                })->whereYear('trjualh.tanggal', '=', $isiFilter->year)
                ->whereMonth('trjualh.tanggal', '=', $isiFilter->month)
                ->where('trjuald.faktorqty', '=', -1)
                ->orderBy('trjualh.adddate')
                ->get();
        } else if ($kriteria == "semua") {
            $data = self::on()->select('trjualh.entiti', 'trjualh.noinvoice', 'trjualh.tanggal', 'trjualh.pembayaran', 'trjuald.sku', 'trjuald.namabarang', 'trjuald.qty', 'trjuald.satuan', 'trjuald.harga', 'trjuald.jumlah', 'trjuald.statusbarang', 'trjualh.adddate', 'trjualh.editdate')
                ->join('trjuald', function ($join) {
                    $join->on('trjuald.entiti', '=', 'trjualh.entiti');
                    $join->on('trjuald.noinvoice', '=', 'trjualh.noinvoice');
                })
                ->where('trjuald.faktorqty', '=', -1)
                ->orderBy('trjualh.adddate')
                ->get();
        } else if ($kriteria == "berdasarkan_tanggal_penjualan") {
            $isiFilter = explode(" - ", $isiFilter);
            $isiFilter[0] = explode("/", $isiFilter[0]);
            $isiFilter[1] = explode("/", $isiFilter[1]);
            $begin = new DateTime($isiFilter[0][2] . "-" . $isiFilter[0][0] . "-" . $isiFilter[0][1]);
            $end = new DateTime($isiFilter[1][2] . "-" . $isiFilter[1][0] . "-" . $isiFilter[1][1]);

            $data = self::on()->select('trjualh.entiti', 'trjualh.noinvoice', 'trjualh.tanggal', 'trjualh.pembayaran', 'trjuald.sku', 'trjuald.namabarang', 'trjuald.qty', 'trjuald.satuan', 'trjuald.harga', 'trjuald.jumlah', 'trjuald.statusbarang', 'trjualh.adddate', 'trjualh.editdate')
                ->join('trjuald', function ($join) {
                    $join->on('trjuald.entiti', '=', 'trjualh.entiti');
                    $join->on('trjuald.noinvoice', '=', 'trjualh.noinvoice');
                })
                ->where('trjualh.tanggal', '>=', $begin)
                ->where('trjualh.tanggal', '<=', $end)
                ->where('trjuald.faktorqty', '=', -1)
                ->orderBy('trjualh.adddate')
                ->get();
        }
        return $data;
    }

    public static function getDailyBuyingPowerChart($begin, $end, $daterange)
    {
        //=============================================================================================================
        //DAILY BUYING POWER CHART
        //Jumlah penjualan / jumlah transaksi
        //SELECT d.tanggal,Sum(h.total) as nominal FROM trjualh as h inner join trjuald as d on h.noinvoice=d.noinvoice and h.entiti=d.entiti WHERE d.tanggal>='2022-09-15' and d.tanggal<='2022-09-30' GROUP BY d.tanggal ORDER BY d.tanggal ASC;

        $buyingPowerDaily = self::on()->select(DB::raw("Round(SUM(total)/count(noinvoice),0) as buyingpower"), 'tanggal')
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

        return $netBuyingPowerDaily;
        //=============================================================================================================
    }

    public static function getHourlyBuyingPowerChart($begin, $end, $tanggal)
    {
        //=============================================================================================================
        //HOURLY BUYING POWER CHART
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

        // dd(DB::getQueryLog());

        for ($begin = 6; $begin <= $end; $begin++) {
            $buyingPowerHourly[$begin] = $buyingPowerHourly[$begin] ?? 0;
            $returBuyingPowerHourly[$begin] = $returBuyingPowerHourly[$begin] ?? 0;
            $netBuyingPowerHourly[$begin] = $buyingPowerHourly[$begin] - $returBuyingPowerHourly[$begin];
        }
        $netBuyingPowerHourly = collect((object)$netBuyingPowerHourly);

        return $netBuyingPowerHourly;
        //=============================================================================================================
    }

    public static function getDailySalesChart($begin, $end, $daterange)
    {
        //=============================================================================================================
        //DAILY SALES CHART
        //SELECT d.tanggal,Sum(h.total) as nominal FROM trjualh as h inner join trjuald as d on h.noinvoice=d.noinvoice and h.entiti=d.entiti WHERE d.tanggal>='2022-09-15' and d.tanggal<='2022-09-30' GROUP BY d.tanggal ORDER BY d.tanggal ASC;

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
                    ->whereColumn(
                        'trjualh.noinvoice',
                        'trjuald.noinvoice'
                    )
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

        return $netSalesDaily;

        //=============================================================================================================
    }

    public static function getHourlySalesChart($begin, $end, $tanggal)
    {
        //=============================================================================================================
        //HOURLY SALES CHART

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

        // dd(DB::getQueryLog());

        for ($begin = 6; $begin <= $end; $begin++) {
            $salesHourly[$begin] = $salesHourly[$begin] ?? 0;
            $returSalesHourly[$begin] = $returSalesHourly[$begin] ?? 0;
            $netSalesHourly[$begin] = $salesHourly[$begin] - $returSalesHourly[$begin];
        }
        $netSalesHourly = collect((object)$netSalesHourly);

        return $netSalesHourly;

        //=============================================================================================================
    }

    public static function getDailyTransactionChart($begin, $end, $daterange)
    {
        //=============================================================================================================
        //DAILY TRANSACTION CHART

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

        return $netTransactionDaily;

        //=============================================================================================================
    }

    public static function getHourlyTransactionChart($begin, $end, $tanggal)
    {
        //=============================================================================================================
        //HOURLY TRANSACTION CHART

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

        return $netTransactionHourly;
        //=============================================================================================================
    }

    public static function getTahunPenjualan()
    {
        $dataTahunPenjualan = self::on()->select(DB::raw("YEAR(tanggal) as tahun"))->groupBy(DB::raw("YEAR(tanggal)"))->orderBy(DB::raw("YEAR(tanggal)"))->get();
        return $dataTahunPenjualan;
    }

    public static function getBulanPenjualan()
    {
        $dataBulanPenjualan = self::on()->select(DB::raw("Concat(MONTHNAME(tanggal),' ',Year(tanggal)) as periode"))->groupBy(DB::raw("Year(tanggal),Month(tanggal),Concat(MONTHNAME(tanggal),' ',Year(tanggal))"))->orderByDesc(DB::raw("Year(tanggal) Desc,Month(tanggal)"))->get();
        return $dataBulanPenjualan;
    }
}
