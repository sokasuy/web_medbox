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

    //    ############# Tambahan Jhonatan #############
    /// vvv tambahan jhonatan vvv
    public static function getGrupMember()
    {
        $data = self::on()
            ->select('grupmember')
            ->wherenotnull('grupmember')
            ->where('grupmember', '!=', '')
            ->groupby('grupmember')
            ->get();
        return $data;
    }

    public static function getSummaryPenjualanGrupMember($kriteria, $isiFilterPeriodeAwal, $isiFilterPeriodeAkhir)
    {
        if ($kriteria == "berdasarkan_periode") {
            $query = self::selectRaw("entiti, grupmember, YEAR(tanggal) AS tahun, MONTH(tanggal) AS bulan, SUM(case when formid='PENJUALAN' then total else total * -1 end) AS total_penjualan")
                ->whereNotNull('grupmember')
                ->groupByRaw('entiti, YEAR(tanggal), MONTH(tanggal), grupmember')
                ->whereBetween('tanggal', [$isiFilterPeriodeAwal, $isiFilterPeriodeAkhir])
                ->get();
        } else if ($kriteria == "berdasarkan_tahun") {
            $query = self::selectRaw("entiti, grupmember, YEAR(tanggal) AS tahun, MONTH(tanggal) AS bulan, SUM(case when formid='PENJUALAN' then total else total * -1 end) AS total_penjualan")
                ->whereNotNull('grupmember')
                ->groupByRaw('entiti, YEAR(tanggal), MONTH(tanggal), grupmember')
                ->whereYear('tanggal', '=', $isiFilterPeriodeAwal)
                ->get();
        } else if ($kriteria == "semua") {
            $query = self::selectRaw("entiti, grupmember, YEAR(tanggal) AS tahun, MONTH(tanggal) AS bulan, SUM(case when formid='PENJUALAN' then total else total * -1 end) AS total_penjualan")
                ->whereNotNull('grupmember')
                ->groupByRaw('entiti, YEAR(tanggal), MONTH(tanggal), grupmember')
                ->get();
        }
        $chartData = [];
        foreach ($query as $data) {
            $label = date('F Y', mktime(0, 0, 0, $data->bulan, 1, $data->tahun)); // Mengonversi bulan dan tahun menjadi label
            $grupmember = $data->grupmember;
            $totalPenjualan = $data->total_penjualan;

            // Mengelompokkan data berdasarkan grupmember
            if (!isset($chartData[$grupmember])) {
                $chartData[$grupmember] = [];
            }

            // Menambahkan data ke dalam array
            $chartData[$grupmember][] = [
                'label' => $label,
                'total_penjualan' => $totalPenjualan
            ];
        }

        // Mengonversi data ke format yang sesuai untuk Chart.js
        $colors = ['#FF5733', '#FFC300', '#b18fe3', '#52eb34', '#56f0b5']; // Contoh warna yang telah ditentukan

        $chartDataset = [];
        foreach ($chartData as $grupmember => $data) {
            $colorIndex = array_search($grupmember, array_keys($chartData)) % count($colors);
            $chartDataset[] = [
                'label' => $grupmember,
                'data' => array_column($data, 'total_penjualan'),
                'backgroundColor' => $colors[$colorIndex] // Warna yang ditentukan untuk setiap grupmember
            ];
        }

        return [$chartDataset, $chartData];
    }


    public static function getSummaryPenjualan($kriteria, $isiFilterPeriodeAwal, $isiFilterPeriodeAkhir, $isiFilterGrupMember)
    {
        DB::enableQueryLog();
        if ($isiFilterGrupMember != '') {
            $whereInValues = array_values($isiFilterGrupMember);
        } else {
            $whereInValues = '';
        }
        if ($kriteria == "berdasarkan_periode") {
            $isiFilterPeriodeAwal = explode(" ", $isiFilterPeriodeAwal);
            $isiFilterPeriodeAkhir = explode(" ", $isiFilterPeriodeAkhir);
            // $isiFilterPeriode[3] = 1;
            $isiFilterPeriodeAwal = date("Y-m-d", strtotime("$isiFilterPeriodeAwal[0]-$isiFilterPeriodeAwal[1]-1"));
            $isiFilterPeriodeAkhir = date("Y-m-t", strtotime("$isiFilterPeriodeAkhir[0]-$isiFilterPeriodeAkhir[1]"));
            // dd($isiFilterPeriodeAkhir);
            $query = self::on()->selectRaw("entiti, YEAR(tanggal) AS tahun, MONTHNAME(tanggal) AS bulan, SUM(case when formid='PENJUALAN' then total else total * -1 end) AS total_penjualan")
                ->whereBetween('tanggal', [$isiFilterPeriodeAwal, $isiFilterPeriodeAkhir])
                ->groupBy('entiti', DB::raw('YEAR(tanggal)'), DB::raw('MONTH(tanggal)'), DB::raw('MONTHNAME(tanggal)'))
                ->orderByRaw('YEAR(tanggal) DESC, MONTH(tanggal) DESC');
            //    ############# Tambahan Jhonatan #############
        } else if ($kriteria == "berdasarkan_tahun") {
            $query = self::on()->selectRaw("entiti, YEAR(tanggal) AS tahun, MONTHNAME(tanggal) AS bulan, SUM(case when formid='PENJUALAN' then total else total * -1 end) AS total_penjualan")
                ->whereYear('tanggal', '=', $isiFilterPeriodeAwal)
                ->groupBy('entiti', DB::raw('YEAR(tanggal)'), DB::raw('MONTH(tanggal)'), DB::raw('MONTHNAME(tanggal)'))
                ->orderByRaw('YEAR(tanggal) DESC, MONTH(tanggal) DESC');
        } else if ($kriteria == "semua") {
            // $query = self::on()->selectRaw('entiti, YEAR(tanggal) AS tahun, MONTHNAME(tanggal) AS bulan, SUM(total) AS total_penjualan')
            //     ->groupByRaw('entiti, YEAR(tanggal), MONTH(tanggal), MONTHNAME(tanggal)')
            //     ->orderByRaw('YEAR(tanggal) DESC, MONTH(tanggal) DESC');
            $query = self::on()->selectRaw("entiti, YEAR(tanggal) AS tahun, MONTHNAME(tanggal) AS bulan, SUM(case when formid='PENJUALAN' then total else total * -1 end) AS total_penjualan")
                ->groupByRaw('entiti, YEAR(tanggal), MONTH(tanggal), MONTHNAME(tanggal)')
                ->orderByRaw('YEAR(tanggal) DESC, MONTH(tanggal) DESC');
        }
        if (!empty($whereInValues)) { // handle untuk wherein
            $query->whereIn('grupmember', $whereInValues);
            // $data = $query->get();
            // dd(DB::getQueryLog());
        }
        // dd(DB::getQueryLog());
        // dd($query);
        $data = $query->get();
        // dd(DB::getQueryLog());
        return $data;
    }

    public static function getPeriodeTahunPenjualan()
    {
        $dataTahunPenjualan = self::on()->select(DB::raw("YEAR(tanggal) as tahun, tanggal"))
            ->groupBy(DB::raw("YEAR(tanggal)"))
            ->orderBy(DB::raw("YEAR(tanggal)"))
            ->get();
        return $dataTahunPenjualan;
    }

    /// ^^^ tambahan jhonatan ^^^
    //    ############# Tambahan Jhonatan #############

    public static function getPenjualanByPeriode($kriteriaPeriode, $isiFilterPeriode, $isiFilterGrupMember)
    {
        //    ############# Tambahan Jhonatan #############
        if ($isiFilterGrupMember != '') {
            $whereInValues = array_values($isiFilterGrupMember);
        } else {
            $whereInValues = '';
        }
        //    ############# Tambahan Jhonatan #############

        if ($kriteriaPeriode == "hari_ini") {
            $query = self::on()->select('trjualh.entiti', 'trjualh.noinvoice', 'trjualh.tanggal', 'trjualh.grupmember', 'trjualh.pembayaran', 'trjuald.sku', 'trjuald.namabarang', 'trjuald.qty', 'trjuald.satuan', 'trjuald.harga', 'trjuald.jumlah', 'trjuald.statusbarang', 'trjualh.adddate', 'trjualh.editdate')
                ->join('trjuald', function ($join) {
                    $join->on('trjuald.entiti', '=', 'trjualh.entiti');
                    $join->on('trjuald.noinvoice', '=', 'trjualh.noinvoice');
                })->where('trjualh.tanggal', '=', $isiFilterPeriode)
                ->where('trjuald.faktorqty', '=', -1)
                ->orderBy('trjualh.adddate');
        } else if ($kriteriaPeriode == "3_hari" || $kriteriaPeriode == "7_hari" || $kriteriaPeriode == "14_hari") {
            $query = self::on()->select('trjualh.entiti', 'trjualh.noinvoice', 'trjualh.tanggal', 'trjualh.grupmember', 'trjualh.pembayaran', 'trjuald.sku', 'trjuald.namabarang', 'trjuald.qty', 'trjuald.satuan', 'trjuald.harga', 'trjuald.jumlah', 'trjuald.statusbarang', 'trjualh.adddate', 'trjualh.editdate')
                ->join('trjuald', function ($join) {
                    $join->on('trjuald.entiti', '=', 'trjualh.entiti');
                    $join->on('trjuald.noinvoice', '=', 'trjualh.noinvoice');
                })->where('trjualh.tanggal', '>=', $isiFilterPeriode)
                ->where('trjuald.faktorqty', '=', -1)
                ->orderBy('trjualh.adddate');
        } else if ($kriteriaPeriode == "bulan_berjalan") {
            $query = self::on()->select('trjualh.entiti', 'trjualh.noinvoice', 'trjualh.tanggal', 'trjualh.grupmember', 'trjualh.pembayaran', 'trjuald.sku', 'trjuald.namabarang', 'trjuald.qty', 'trjuald.satuan', 'trjuald.harga', 'trjuald.jumlah', 'trjuald.statusbarang', 'trjualh.adddate', 'trjualh.editdate')
                ->join('trjuald', function ($join) {
                    $join->on('trjuald.entiti', '=', 'trjualh.entiti');
                    $join->on('trjuald.noinvoice', '=', 'trjualh.noinvoice');
                })->whereYear('trjualh.tanggal', '=', $isiFilterPeriode->year)
                ->whereMonth('trjualh.tanggal', '=', $isiFilterPeriode->month)
                ->where('trjuald.faktorqty', '=', -1)
                ->orderBy('trjualh.adddate');
        } else if ($kriteriaPeriode == "semua") {
            $query = self::on()->select('trjualh.entiti', 'trjualh.noinvoice', 'trjualh.tanggal', 'trjualh.grupmember', 'trjualh.pembayaran', 'trjuald.sku', 'trjuald.namabarang', 'trjuald.qty', 'trjuald.satuan', 'trjuald.harga', 'trjuald.jumlah', 'trjuald.statusbarang', 'trjualh.adddate', 'trjualh.editdate')
                ->join('trjuald', function ($join) {
                    $join->on('trjuald.entiti', '=', 'trjualh.entiti');
                    $join->on('trjuald.noinvoice', '=', 'trjualh.noinvoice');
                })
                ->where('trjuald.faktorqty', '=', -1)
                ->orderBy('trjualh.adddate');
        } else if ($kriteriaPeriode == "berdasarkan_tanggal_penjualan") {
            $isiFilterPeriode = explode(" - ", $isiFilterPeriode);
            $isiFilterPeriode[0] = explode("/", $isiFilterPeriode[0]);
            $isiFilterPeriode[1] = explode("/", $isiFilterPeriode[1]);
            $begin = new DateTime($isiFilterPeriode[0][2] . "-" . $isiFilterPeriode[0][0] . "-" . $isiFilterPeriode[0][1]);
            $end = new DateTime($isiFilterPeriode[1][2] . "-" . $isiFilterPeriode[1][0] . "-" . $isiFilterPeriode[1][1]);

            $query = self::on()->select('trjualh.entiti', 'trjualh.noinvoice', 'trjualh.tanggal', 'trjualh.grupmember', 'trjualh.pembayaran', 'trjuald.sku', 'trjuald.namabarang', 'trjuald.qty', 'trjuald.satuan', 'trjuald.harga', 'trjuald.jumlah', 'trjuald.statusbarang', 'trjualh.adddate', 'trjualh.editdate')
                ->join('trjuald', function ($join) {
                    $join->on('trjuald.entiti', '=', 'trjualh.entiti');
                    $join->on('trjuald.noinvoice', '=', 'trjualh.noinvoice');
                })
                ->where('trjualh.tanggal', '>=', $begin)
                ->where('trjualh.tanggal', '<=', $end)
                ->where('trjuald.faktorqty', '=', -1)
                ->orderBy('trjualh.adddate');
        }
        if (!empty($whereInValues)) { // handle untuk wherein
            $query->whereIn('trjualh.grupmember', $whereInValues);
        }
        $data = $query->get();
        return $data;
    }

    public static function getDailyBuyingPowerChart($begin, $end, $daterange)
    {
        //=============================================================================================================
        //DAILY BUYING POWER CHART
        //Jumlah penjualan / jumlah transaksi
        //SELECT d.tanggal,Sum(h.total) as nominal FROM trjualh as h inner join trjuald as d on h.noinvoice=d.noinvoice and h.entiti=d.entiti WHERE d.tanggal>='2022-09-15' and d.tanggal<='2022-09-30' GROUP BY d.tanggal ORDER BY d.tanggal ASC;

        /*  $buyingPowerDaily = self::on()->select(DB::raw("Round(SUM(total)/count(noinvoice),0) as buyingpower"), 'tanggal')
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
            ->pluck('buyingpower', 'tanggal'); */
        // dd(DB::getQueryLog());

        $salesDaily = self::getDailySalesChart($begin, $end, $daterange);

        //SALES RETURN
        /* $returBuyingPowerDaily = self::on()->select(DB::raw("Round(SUM(total)/count(noinvoice),0) as retur"), 'tanggal')
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
            ->pluck('retur', 'tanggal'); */
        // dd(DB::getQueryLog());
        $transactionDaily = self::getDailyTransactionChart($begin, $end, $daterange);

        foreach ($daterange as $date) {
            $onlyDate = $date->Format('Y-m-d');
            $labelsDate = $date->Format('m/d');
            /* $buyingPowerDaily[$onlyDate] = $buyingPowerDaily[$onlyDate] ?? 0;
            $returBuyingPowerDaily[$onlyDate] = $returBuyingPowerDaily[$onlyDate] ?? 0;
            $netBuyingPowerDaily[$onlyDate] = $buyingPowerDaily[$onlyDate] - $returBuyingPowerDaily[$onlyDate]; */

            // $salesDaily[$onlyDate] = $salesDaily[$onlyDate] ?? 0;
            // $transactionDaily[$onlyDate] = $transactionDaily[$onlyDate] ?? 1; //Pakai 0 karena menghindari error kalau tidak ada transaksi, seharusnya seh gak mungkin, tapi buat jaga2 saja
            // $transactionDaily[$onlyDate] = $transactionDaily[$onlyDate] == 0 ? 1 : $transactionDaily[$onlyDate];
            // $netBuyingPowerDaily[$onlyDate] = Round($salesDaily[$onlyDate] / $transactionDaily[$onlyDate]);

            $salesDaily[$labelsDate] = $salesDaily[$labelsDate] ?? 0;
            $transactionDaily[$labelsDate] = $transactionDaily[$labelsDate] ?? 1; //Pakai 0 karena menghindari error kalau tidak ada transaksi, seharusnya seh gak mungkin, tapi buat jaga2 saja
            $transactionDaily[$labelsDate] = $transactionDaily[$labelsDate] == 0 ? 1 : $transactionDaily[$labelsDate];
            $netBuyingPowerDaily[$labelsDate] = Round($salesDaily[$labelsDate] / $transactionDaily[$labelsDate]);
        }
        $netBuyingPowerDaily = collect((object)$netBuyingPowerDaily);

        return $netBuyingPowerDaily;
        //=============================================================================================================
    }

    public static function getHourlyBuyingPowerChart($jamBuka, $jamTutup, $begin, $end)
    {
        //=============================================================================================================
        //HOURLY BUYING POWER CHART
        /* $buyingPowerHourly = self::on()->select(DB::raw("Round(SUM(total)/count(noinvoice),0) as buyingpower"), DB::raw("Hour(adddate) as jam"))
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
            ->pluck('buyingpower', 'jam'); */

        // 19 Feb 2024 diganti jadi range
        $salesHourly = self::getHourlySalesChart($jamBuka, $jamTutup, $begin, $end);

        //SALES RETURN
        /* $returBuyingPowerHourly = self::on()->select(DB::raw("Round(SUM(total)/count(noinvoice),0) as retur"), DB::raw("Hour(adddate) as jam"))
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
            ->pluck('retur', 'jam'); */

        // 19 Feb 2024 diganti jadi range
        $transactionHourly = self::getHourlyTransactionChart($jamBuka, $jamTutup, $begin, $end);

        // dd(DB::getQueryLog());

        for ($jamBuka = 6; $jamBuka <= $jamTutup; $jamBuka++) {
            /* $buyingPowerHourly[$begin] = $buyingPowerHourly[$begin] ?? 0;
            $returBuyingPowerHourly[$begin] = $returBuyingPowerHourly[$begin] ?? 0;
            $netBuyingPowerHourly[$begin] = $buyingPowerHourly[$begin] - $returBuyingPowerHourly[$begin]; */

            $salesHourly[$jamBuka] = $salesHourly[$jamBuka] ?? 0;
            $transactionHourly[$jamBuka] = $transactionHourly[$jamBuka] ?? 1; //Pakai 0 karena menghindari error kalau tidak ada transaksi, seharusnya seh gak mungkin, tapi buat jaga2 saja
            $transactionHourly[$jamBuka] = $transactionHourly[$jamBuka] == 0 ? 1 : $transactionHourly[$jamBuka];
            $netBuyingPowerHourly[$jamBuka] = Round($salesHourly[$jamBuka] / $transactionHourly[$jamBuka]);
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

        $salesDaily = self::on()->select(DB::raw("Round(SUM(total),0) as penjualan"), 'tanggal')
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
        $returSalesDaily = self::on()->select(DB::raw("Round(SUM(total),0) as retur"), 'tanggal')
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
            $labelsDate = $date->Format('m/d');

            // $salesDaily[$onlyDate] = $salesDaily[$onlyDate] ?? 0;
            // $returSalesDaily[$onlyDate] = $returSalesDaily[$onlyDate] ?? 0;
            // $netSalesDaily[$onlyDate] = $salesDaily[$onlyDate] - $returSalesDaily[$onlyDate];

            $salesDaily[$labelsDate] = $salesDaily[$onlyDate] ?? 0;
            $returSalesDaily[$labelsDate] = $returSalesDaily[$onlyDate] ?? 0;
            $netSalesDaily[$labelsDate] = $salesDaily[$labelsDate] - $returSalesDaily[$labelsDate];
        }
        $netSalesDaily = collect((object)$netSalesDaily);

        return $netSalesDaily;

        //=============================================================================================================
    }

    public static function getHourlySalesChart($jamBuka, $jamTutup, $begin, $end)
    {
        //=============================================================================================================
        //HOURLY SALES CHART

        $salesHourly = self::on()->select(DB::raw("Round(SUM(total),0) as penjualan"), DB::raw("Hour(adddate) as jam"))
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
            ->where('tanggal', '>=', $begin)->where('tanggal', '<=', $end)
            ->groupBy(DB::raw("Hour(adddate)"))
            ->orderBy(DB::raw("Hour(adddate)"))
            ->pluck('penjualan', 'jam');

        //SALES RETURN
        $returSalesHourly = self::on()->select(DB::raw("Round(SUM(total),0) as retur"), DB::raw("Hour(adddate) as jam"))
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('trjuald')
                    ->whereColumn('trjualh.entiti', 'trjuald.entiti')
                    ->whereColumn('trjualh.noinvoice', 'trjuald.noinvoice')
                    ->where('trjuald.faktorqty', '=', 1);
            })
            ->where('tanggal', '>=', $begin)->where('tanggal', '<=', $end)
            ->groupBy(DB::raw("Hour(adddate)"))
            ->orderBy(DB::raw("Hour(adddate)"))
            ->pluck('retur', 'jam');

        // dd(DB::getQueryLog());

        for ($jamBuka = 6; $jamBuka <= $jamTutup; $jamBuka++) {
            $salesHourly[$jamBuka] = $salesHourly[$jamBuka] ?? 0;
            $returSalesHourly[$jamBuka] = $returSalesHourly[$jamBuka] ?? 0;
            $netSalesHourly[$jamBuka] = $salesHourly[$jamBuka] - $returSalesHourly[$jamBuka];
        }
        $netSalesHourly = collect((object)$netSalesHourly);

        return $netSalesHourly;

        //=============================================================================================================
    }

    public static function getDailyTransactionChart($begin, $end, $daterange)
    {
        //=============================================================================================================
        //DAILY TRANSACTION CHART

        $transactionDaily = self::on()->select(DB::raw("Count(noinvoice) as transaksi"), 'tanggal')
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
        $returTransactionDaily = self::on()->select(DB::raw("Count(noinvoice) as retur"), 'tanggal')
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
            $labelsDate = $date->Format('m/d');

            // $transactionDaily[$onlyDate] = $transactionDaily[$onlyDate] ?? 0;
            // $returTransactionDaily[$onlyDate] = $returTransactionDaily[$onlyDate] ?? 0;
            // $netTransactionDaily[$onlyDate] = $transactionDaily[$onlyDate] - $returTransactionDaily[$onlyDate];

            $transactionDaily[$labelsDate] = $transactionDaily[$onlyDate] ?? 0;
            $returTransactionDaily[$labelsDate] = $returTransactionDaily[$onlyDate] ?? 0;
            $netTransactionDaily[$labelsDate] = $transactionDaily[$labelsDate] - $returTransactionDaily[$labelsDate];
        }
        $netTransactionDaily = collect((object)$netTransactionDaily);

        return $netTransactionDaily;

        //=============================================================================================================
    }

    public static function getHourlyTransactionChart($jamBuka, $jamTutup, $begin, $end)
    {
        //=============================================================================================================
        //HOURLY TRANSACTION CHART

        $transactionHourly = self::on()->select(DB::raw("Count(noinvoice) as transaksi"), DB::raw("Hour(adddate) as jam"))
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('trjuald')
                    ->whereColumn('trjualh.entiti', 'trjuald.entiti')
                    ->whereColumn('trjualh.noinvoice', 'trjuald.noinvoice')
                    ->where('trjuald.faktorqty', '=', -1);
            })
            ->where('tanggal', '>=', $begin)->where('tanggal', '<=', $end)
            ->groupBy(DB::raw("Hour(adddate)"))
            ->orderBy(DB::raw("Hour(adddate)"))
            ->pluck(
                'transaksi',
                'jam'
            );

        //TRANSACTION RETURN
        $returTransactionHourly = self::on()->select(DB::raw("Count(noinvoice) as retur"), DB::raw("Hour(adddate) as jam"))
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
            ->where('tanggal', '>=', $begin)->where('tanggal', '<=', $end)
            ->groupBy(DB::raw("Hour(adddate)"))
            ->orderBy(DB::raw("Hour(adddate)"))
            ->pluck('retur', 'jam');

        // dd(DB::getQueryLog());

        for ($jamBuka = 6; $jamBuka <= $jamTutup; $jamBuka++) {
            $transactionHourly[$jamBuka] = $transactionHourly[$jamBuka] ?? 0;
            $returTransactionHourly[$jamBuka] = $returTransactionHourly[$jamBuka] ?? 0;
            $netTransactionHourly[$jamBuka] = $transactionHourly[$jamBuka] - $returTransactionHourly[$jamBuka];
        }
        $netTransactionHourly = collect((object)$netTransactionHourly);

        return $netTransactionHourly;
        //=============================================================================================================
    }

    public static function getTahunPenjualan()
    {
        $dataTahunPenjualan = self::on()->select(DB::raw("YEAR(tanggal) as tahun"))->groupBy(DB::raw("YEAR(tanggal)"))->orderByDesc(DB::raw("YEAR(tanggal)"))->get();
        return $dataTahunPenjualan;
    }

    public static function getBulanPenjualan()
    {
        $dataBulanPenjualan = self::on()->select(DB::raw("Concat(MONTHNAME(tanggal),' ',Year(tanggal)) as periode"))->groupBy(DB::raw("Year(tanggal),Month(tanggal),Concat(MONTHNAME(tanggal),' ',Year(tanggal))"))->orderByDesc(DB::raw("Year(tanggal) Desc,Month(tanggal)"))->get();
        return $dataBulanPenjualan;
    }

    public static function getPenjualanByPeriodeChart($kriteria, $isiFilter)
    {
        if ($kriteria == "bulanan") {
            $data = self::on()->select(DB::raw("SUM(total) as totaljual"), DB::raw("MONTHNAME(tanggal) as bulan"))
                ->whereExists(function ($query) use ($isiFilter) {
                    $query->select(DB::raw(1))
                        ->from('trjuald')
                        ->whereColumn('trjualh.entiti', 'trjuald.entiti')
                        ->whereColumn('trjualh.noinvoice', 'trjuald.noinvoice')
                        ->where('trjuald.faktorqty', '=', -1)
                        ->whereYear('trjualh.tanggal', '=', $isiFilter);
                })
                ->whereYear('tanggal', '=', $isiFilter)
                ->groupBy(DB::raw("MONTHNAME(tanggal)"))
                ->pluck('totaljual', 'bulan');
        }
        return $data;
    }

    public static function getReturPenjualanByPeriodeChart($kriteria, $isiFilter)
    {
        if ($kriteria == "bulanan") {
            $data = self::on()->select(DB::raw("SUM(total) as totalretur"), DB::raw("MONTHNAME(tanggal) as bulan"))
                ->whereExists(function ($query) use ($isiFilter) {
                    $query->select(DB::raw(1))
                        ->from('trjuald')
                        ->whereColumn('trjualh.entiti', 'trjuald.entiti')
                        ->whereColumn('trjualh.noinvoice', 'trjuald.noinvoice')
                        ->where('trjuald.faktorqty', '=', 1)
                        ->whereYear('trjualh.tanggal', '=', $isiFilter);
                })
                ->whereYear('tanggal', '=', $isiFilter)
                ->groupBy(DB::raw("MONTHNAME(tanggal)"))
                ->pluck('totalretur', 'bulan');
        }
        return $data;
    }

    public static function getBestsellerByPeriodeChart($kriteria, $isiFilter)
    {
        if ($kriteria == "tahun") {
            $data = self::on()->join('trjuald', function ($join) {
                $join->on('trjualh.noinvoice', '=', 'trjuald.noinvoice');
                $join->on('trjualh.entiti', '=', 'trjuald.entiti');
            })
                ->select(DB::raw("(SUM(trjuald.qty*trjuald.faktorqty))*-1 as qtyterjual"), 'namabarang')
                ->where('trjuald.faktorqty', '=', -1)
                ->whereYear('trjualh.tanggal', '=', $isiFilter)
                ->groupBy('namabarang')
                ->OrderByDesc(DB::raw("qtyterjual"))
                ->Limit(10)
                ->pluck('qtyterjual', 'namabarang');
        } else if ($kriteria == "bulan") {
            $isiFilter = explode(" ", $isiFilter);
            foreach (Helper::$months as $key => $bulan) {
                if ($bulan == $isiFilter[0]) {
                    $isiFilter[0] = $key + 1;
                }
            }
            $data = self::on()->join('trjuald', function ($join) {
                $join->on('trjualh.noinvoice', '=', 'trjuald.noinvoice');
                $join->on('trjualh.entiti', '=', 'trjuald.entiti');
            })
                ->select(DB::raw("(SUM(trjuald.qty*trjuald.faktorqty))*-1 as qtyterjual"), 'namabarang')
                ->where('trjuald.faktorqty', '=', -1)
                ->whereYear('trjualh.tanggal', '=', $isiFilter[1])
                ->whereMonth('trjualh.tanggal', '=', $isiFilter[0])
                ->groupBy('namabarang')
                ->OrderByDesc(DB::raw("qtyterjual"))
                ->Limit(10)
                ->pluck('qtyterjual', 'namabarang');
        } elseif ($kriteria == "tanggal") {
            $isiFilter = explode(" - ", $isiFilter);
            $isiFilter[0] = explode("/", $isiFilter[0]);
            $isiFilter[1] = explode("/", $isiFilter[1]);
            $periodeAwal = $isiFilter[0][2] . "-" . $isiFilter[0][0] . "-" . $isiFilter[0][1];
            $periodeAkhir = $isiFilter[1][2] . "-" . $isiFilter[1][0] . "-" . $isiFilter[1][1];
            $data = self::on()->join('trjuald', function ($join) {
                $join->on('trjualh.noinvoice', '=', 'trjuald.noinvoice');
                $join->on('trjualh.entiti', '=', 'trjuald.entiti');
            })
                ->select(DB::raw("(SUM(trjuald.qty*trjuald.faktorqty))*-1 as qtyterjual"), 'namabarang')
                ->where('trjuald.faktorqty', '=', -1)
                ->where('trjualh.tanggal', '>=', $periodeAwal)->where('trjualh.tanggal', '<=', $periodeAkhir)
                ->groupBy('namabarang')
                ->OrderByDesc(DB::raw("qtyterjual"))
                ->Limit(10)
                ->pluck('qtyterjual', 'namabarang');
        }
        return $data;
    }
}
