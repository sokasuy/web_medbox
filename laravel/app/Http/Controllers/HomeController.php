<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Sales;
use App\Models\StokBarang;
use App\Models\MsKontak;
use App\Models\Helper;
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
        // $isiFilter = "10/25/2022 - 10/25/2022";
        // $isiFilter = explode(" - ", $isiFilter);
        // $isiFilter[0] = explode("/", $isiFilter[0]);
        // $isiFilter[1] = explode("/", $isiFilter[1]);
        // dd($isiFilter[0][2] . "-" . $isiFilter[0][0] . "-" . $isiFilter[0][1]);
        //=============================================================================================================
        // COMBO BOX DI DASHBOARD
        $dataSupplier = MsKontak::getSupplier();
        $dataCbo['dataSupplier'] = $dataSupplier;

        $dataTahunPembelian = Purchase::getTahunPembelian();
        $dataCbo['tahunPembelian'] = $dataTahunPembelian;

        $dataTahunPenjualan = Sales::getTahunPenjualan();
        $dataCbo['tahunPenjualan'] = $dataTahunPenjualan;

        $dataBulanPenjualan = Sales::getBulanPenjualan();
        $dataCbo['bulanPenjualan'] = $dataBulanPenjualan;
        //=============================================================================================================

        // DB::enableQueryLog();
        //=============================================================================================================
        //CHARTS
        //PURCHASING
        //SELECT SUM(h.subtotal) as totalbeli,MONTHNAME(h.tanggal) as bulan FROM trterimah as h WHERE EXISTS(SELECT 1 FROM trterimad WHERE entiti=h.entiti and noterima=h.noterima and faktorqty=1) AND year(tanggal)>='2022' GROUP BY MONTHNAME(tanggal);
        /* $purchase = Purchase::select(DB::raw("SUM(subtotal) as totalbeli"), DB::raw("MONTHNAME(tanggal) as bulan"))
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('trterimad')
                    ->whereColumn('trterimah.entiti', 'trterimad.entiti')
                    ->whereColumn('trterimah.noterima', 'trterimad.noterima')
                    ->where('trterimad.faktorqty', '=', 1);
            })
            ->whereYear('tanggal', '=', Carbon::now()->format('Y'))
            ->groupBy(DB::raw("MONTHNAME(tanggal)"))
            ->pluck('totalbeli', 'bulan'); */
        $purchase = Purchase::getPembelianByPeriodeChart("bulanan", Carbon::now()->format('Y'));
        // dd(DB::getQueryLog());

        //PURCHASE RETURN
        //SELECT SUM(h.subtotal) as totalbeli,MONTHNAME(h.tanggal) as bulan FROM trterimah as h WHERE EXISTS(SELECT 1 FROM trterimad WHERE entiti=h.entiti and noterima=h.noterima and faktorqty=-1) AND year(tanggal)>='2022' GROUP BY MONTHNAME(tanggal);
        /* $returPurchase = Purchase::select(DB::raw("SUM(subtotal) as totalretur"), DB::raw("MONTHNAME(tanggal) as bulan"))
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('trterimad')
                    ->whereColumn('trterimah.entiti', 'trterimad.entiti')
                    ->whereColumn('trterimah.noterima', 'trterimad.noterima')
                    ->where('trterimad.faktorqty', '=', -1)
                    ->whereYear('trterimah.tanggal', '=', Carbon::now()->format('Y'));
            })
            ->whereYear('tanggal', '=', Carbon::now()->format('Y'))
            ->groupBy(DB::raw("MONTHNAME(tanggal)"))
            ->pluck('totalretur', 'bulan'); */
        $returPurchase = Purchase::getReturPembelianByPeriodeChart("bulanan", Carbon::now()->format('Y'));

        foreach (Helper::$months as $bulan) {
            $purchase[$bulan] = $purchase[$bulan] ?? 0;
            $returPurchase[$bulan] = $returPurchase[$bulan] ?? 0;
            $netPurchase[$bulan] = $purchase[$bulan] - $returPurchase[$bulan];
        }
        $netPurchase = collect((object)$netPurchase);
        $labels['purchase'] = $netPurchase->keys();
        $data['purchase'] = $netPurchase->values();
        //=============================================================================================================

        //=============================================================================================================
        //SALES
        //SELECT SUM(h.total) as totaljual,MONTHNAME(h.tanggal) as bulan FROM trjualh as h WHERE EXISTS(SELECT 1 FROM trjuald WHERE entiti=h.entiti and noinvoice=h.noinvoice and faktorqty=-1) AND year(h.tanggal)>='2022' GROUP BY MONTHNAME(h.tanggal);
        /* $sales = Sales::select(DB::raw("SUM(total) as totaljual"), DB::raw("MONTHNAME(tanggal) as bulan"))
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('trjuald')
                    ->whereColumn('trjualh.entiti', 'trjuald.entiti')
                    ->whereColumn('trjualh.noinvoice', 'trjuald.noinvoice')
                    ->where('trjuald.faktorqty', '=', -1)
                    ->whereYear('trjualh.tanggal', '=', Carbon::now()->format('Y'));
            })
            ->whereYear('tanggal', '=', Carbon::now()->format('Y'))
            ->groupBy(DB::raw("MONTHNAME(tanggal)"))
            ->pluck('totaljual', 'bulan'); */
        $sales = Sales::getPenjualanByPeriodeChart("bulanan", Carbon::now()->format('Y'));

        //SALES RETURN
        /* $returSales = Sales::select(DB::raw("SUM(total) as totalretur"), DB::raw("MONTHNAME(tanggal) as bulan"))
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('trjuald')
                    ->whereColumn('trjualh.entiti', 'trjuald.entiti')
                    ->whereColumn('trjualh.noinvoice', 'trjuald.noinvoice')
                    ->where('trjuald.faktorqty', '=', 1)
                    ->whereYear('trjualh.tanggal', '=', Carbon::now()->format('Y'));
            })
            ->whereYear('tanggal', '=', Carbon::now()->format('Y'))
            ->groupBy(DB::raw("MONTHNAME(tanggal)"))
            ->pluck('totalretur', 'bulan'); */
        $returSales = Sales::getReturPenjualanByPeriodeChart("bulanan", Carbon::now()->format('Y'));

        // dd(DB::getQueryLog());
        foreach (Helper::$months as $bulan) {
            // if (($sales[$bulan]) ?? null) {
            //     $jual[$bulan] = $sales[$bulan];
            // } else {
            //     $jual[$bulan] = 0;
            // }
            $sales[$bulan] = $sales[$bulan] ?? 0;
            // if (($returSales[$bulan]) ?? null) {
            //     $kembalikanJual[$bulan] = $returSales[$bulan];
            // } else {
            //     $kembalikanJual[$bulan] = 0;
            // }
            $returSales[$bulan] = $returSales[$bulan] ?? 0;
            $netSales[$bulan] = $sales[$bulan] - $returSales[$bulan];
        }
        $netSales = collect((object)$netSales);

        $labels['sales'] = $netSales->keys();
        $data['sales'] = $netSales->values();
        //=============================================================================================================

        //=============================================================================================================
        // PROFIT AND LOSS
        //HPP SALES
        //SELECT SUM(s.qty*s.hpp) as hpp,MONTHNAME(h.tanggal) as bulan FROM trjualh as h inner join stokbarang as s on h.noinvoice=s.kodereferensi and h.entiti=s.entiti WHERE EXISTS(SELECT 1 FROM trjuald WHERE entiti=h.entiti and noinvoice=h.noinvoice and faktorqty=-1) AND year(h.tanggal)>='2022' GROUP BY MONTHNAME(h.tanggal);
        /* $hppSales = StokBarang::join('trjualh', function ($join) {
            $join->on('trjualh.noinvoice', '=', 'stokbarang.kodereferensi');
            $join->on('trjualh.entiti', '=', 'stokbarang.entiti');
        })
            ->select(DB::raw("SUM(stokbarang.qty*stokbarang.hpp) as hppsales"), DB::raw("MONTHNAME(trjualh.tanggal) as bulan"))
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('trjuald')
                    ->whereColumn('trjualh.entiti', 'trjuald.entiti')
                    ->whereColumn('trjualh.noinvoice', 'trjuald.noinvoice')
                    ->where('trjuald.faktorqty', '=', -1)
                    ->whereYear('trjualh.tanggal', '=', Carbon::now()->format('Y'));
            })
            ->whereYear('trjualh.tanggal', '=', Carbon::now()->format('Y'))
            ->groupBy(DB::raw("MONTHNAME(trjualh.tanggal)"))
            ->pluck('hppsales', 'bulan'); */

        // *** 20 Feb 2024 di comment agar pada saat login tidak terlalu lama, membuat chart profit and loss tidak akan ditarik otomatis di awal login
        // *** 25 Feb 2024 dibuka lagi untuk testing
        // $hppSales = StokBarang::getHPPPenjualan("bulanan", Carbon::now()->format('Y'));

        //HPP SALES RETUR
        /* $hppReturSales = StokBarang::join('trjualh', function ($join) {
            $join->on('trjualh.noinvoice', '=', 'stokbarang.kodereferensi');
            $join->on('trjualh.entiti', '=', 'stokbarang.entiti');
        })
            ->select(DB::raw("SUM(stokbarang.qty*stokbarang.hpp) as hppretursales"), DB::raw("MONTHNAME(trjualh.tanggal) as bulan"))
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('trjuald')
                    ->whereColumn('trjualh.entiti', 'trjuald.entiti')
                    ->whereColumn('trjualh.noinvoice', 'trjuald.noinvoice')
                    ->where('trjuald.faktorqty', '=', 1)
                    ->whereYear('trjualh.tanggal', '=', Carbon::now()->format('Y'));
            })
            ->whereYear('trjualh.tanggal', '=', Carbon::now()->format('Y'))
            ->groupBy(DB::raw("MONTHNAME(trjualh.tanggal)"))
            ->pluck('hppretursales', 'bulan'); */
        $hppReturSales = StokBarang::getReturHPPPenjualan("bulanan", Carbon::now()->format('Y'));
        // dd(DB::getQueryLog());

        foreach (Helper::$months as $bulan) {
            // if (($hppSales[$bulan]) ?? null) {
            //     $hppJual[$bulan] = $hppSales[$bulan];
            // } else {
            //     $hppJual[$bulan] = 0;
            // }
            $hppSales[$bulan] = $hppSales[$bulan] ?? 0;
            // if (($hppReturSales[$bulan]) ?? null) {
            //     $hppKembalikanJual[$bulan] = $hppReturSales[$bulan];
            // } else {
            //     $hppKembalikanJual[$bulan] = 0;
            // }
            $hppReturSales[$bulan] = $hppReturSales[$bulan] ?? 0;
            $netHpp[$bulan] = $hppSales[$bulan] - $hppReturSales[$bulan];
            $profit[$bulan] = (float)$netSales[$bulan] - (float)$netHpp[$bulan];
        }
        $profit = collect((object)$profit);

        $labels['profitloss'] = $profit->keys();
        $data['profitloss'] = $profit->values();
        //=============================================================================================================

        //=============================================================================================================
        //OBAT TERLARIS
        //SELECT (SUM(d.qty*d.faktorqty))*-1 as qtyterjual,d.namabarang FROM trjualh as h inner join trjuald as d on h.entiti=d.entiti and h.noinvoice=d.noinvoice WHERE MONTH(h.tanggal)='10' and year(h.tanggal)>='2022' GROUP BY d.namabarang ORDER BY qtyterjual DESC LIMIT 10;
        /* $bestSeller = Sales::join('trjuald', function ($join) {
            $join->on('trjualh.noinvoice', '=', 'trjuald.noinvoice');
            $join->on('trjualh.entiti', '=', 'trjuald.entiti');
        })
            ->select(DB::raw("(SUM(trjuald.qty*trjuald.faktorqty))*-1 as qtyterjual"), 'namabarang')
            ->where('trjuald.faktorqty', '=', -1)
            ->whereYear('trjualh.tanggal', '=', Carbon::now()->format('Y'))
            ->groupBy('namabarang')
            ->OrderByDesc(DB::raw("qtyterjual"))
            ->Limit(10)
            ->pluck('qtyterjual', 'namabarang'); */

        $bestSeller = Sales::getBestsellerByPeriodeChart("tahun", Carbon::now()->format('Y'));
        // dd(DB::getQueryLog());
        $labels['bestseller'] = $bestSeller->keys();
        $data['bestseller'] = $bestSeller->values();
        //=============================================================================================================

        // 4 Oktober 2023 menambahkan kirim tahun ke halaman homepage
        $tahunSekarang = Carbon::now()->format('Y');
        return view('home', compact('labels', 'data', 'dataCbo', 'tahunSekarang'));
    }

    public function refreshPurchaseChart(Request $request)
    {
        $supplier = $request->get('supplier');
        $tahun = $request->get('tahun');

        if ($supplier == "SEMUA") {
            //PURCHASING
            /* $purchase = Purchase::select(DB::raw("SUM(subtotal) as totalbeli"), DB::raw("MONTHNAME(tanggal) as bulan"))
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('trterimad')
                        ->whereColumn('trterimah.entiti', 'trterimad.entiti')
                        ->whereColumn('trterimah.noterima', 'trterimad.noterima')
                        ->where('trterimad.faktorqty', '=', 1);
                })
                ->whereYear('tanggal', '=', $tahun)
                ->groupBy(DB::raw("MONTHNAME(tanggal)"))
                ->pluck('totalbeli', 'bulan'); */
            $purchase = Purchase::getPembelianByPeriodeChart("bulanan", $tahun);
            //PURCHASE RETURN
            //SELECT SUM(h.subtotal) as totalbeli,MONTHNAME(h.tanggal) as bulan FROM trterimah as h WHERE EXISTS(SELECT 1 FROM trterimad WHERE entiti=h.entiti and noterima=h.noterima and faktorqty=-1) AND year(tanggal)>='2022' GROUP BY MONTHNAME(tanggal);
            /* $returPurchase = Purchase::select(DB::raw("SUM(subtotal) as totalretur"), DB::raw("MONTHNAME(tanggal) as bulan"))
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('trterimad')
                        ->whereColumn('trterimah.entiti', 'trterimad.entiti')
                        ->whereColumn('trterimah.noterima', 'trterimad.noterima')
                        ->where('trterimad.faktorqty', '=', -1);
                })
                ->whereYear('tanggal', '=', $tahun)
                ->groupBy(DB::raw("MONTHNAME(tanggal)"))
                ->pluck('totalretur', 'bulan'); */
            $returPurchase = Purchase::getReturPembelianByPeriodeChart("bulanan", $tahun);
        } else {
            //PURCHASING
            /* $purchase = Purchase::select(DB::raw("SUM(subtotal) as totalbeli"), DB::raw("MONTHNAME(tanggal) as bulan"))
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('trterimad')
                        ->whereColumn('trterimah.entiti', 'trterimad.entiti')
                        ->whereColumn('trterimah.noterima', 'trterimad.noterima')
                        ->where('trterimad.faktorqty', '=', 1);
                })
                ->whereYear('tanggal', '=', $tahun)
                ->where('kodekontak', '=', $supplier)
                ->groupBy(DB::raw("MONTHNAME(tanggal)"))
                ->pluck('totalbeli', 'bulan'); */
            $purchase = Purchase::getPembelianByPeriodeChart("bulanan", $tahun, $supplier);

            //PURCHASE RETURN
            //SELECT SUM(h.subtotal) as totalbeli,MONTHNAME(h.tanggal) as bulan FROM trterimah as h WHERE EXISTS(SELECT 1 FROM trterimad WHERE entiti=h.entiti and noterima=h.noterima and faktorqty=-1) AND year(tanggal)>='2022' GROUP BY MONTHNAME(tanggal);
            /* $returPurchase = Purchase::select(DB::raw("SUM(subtotal) as totalretur"), DB::raw("MONTHNAME(tanggal) as bulan"))
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('trterimad')
                        ->whereColumn('trterimah.entiti', 'trterimad.entiti')
                        ->whereColumn('trterimah.noterima', 'trterimad.noterima')
                        ->where('trterimad.faktorqty', '=', -1);
                })
                ->whereYear('tanggal', '=', $tahun)
                ->where('kodekontak', '=', $supplier)
                ->groupBy(DB::raw("MONTHNAME(tanggal)"))
                ->pluck('totalretur', 'bulan'); */
            $returPurchase = Purchase::getReturPembelianByPeriodeChart("bulanan", $tahun, $supplier);
        }

        foreach (Helper::$months as $bulan) {
            $purchase[$bulan] = $purchase[$bulan] ?? 0;
            $returPurchase[$bulan] = $returPurchase[$bulan] ?? 0;
            $netPurchase[$bulan] = $purchase[$bulan] - $returPurchase[$bulan];
        }
        $netPurchase = collect((object)$netPurchase);

        $ajaxData['labels'] = $netPurchase->keys();
        $ajaxData['data'] = $netPurchase->values();

        return response()->json(
            array(
                'status' => 'ok',
                'msg' => $ajaxData
            ),
            200
        );
    }

    public function refreshSalesChart(Request $request)
    {
        $tahun = $request->get('tahun');

        // $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        //SALES
        /* $sales = Sales::select(DB::raw("SUM(total) as totaljual"), DB::raw("MONTHNAME(tanggal) as bulan"))
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('trjuald')
                    ->whereColumn('trjualh.entiti', 'trjuald.entiti')
                    ->whereColumn('trjualh.noinvoice', 'trjuald.noinvoice')
                    ->where('trjuald.faktorqty', '=', -1);
            })
            ->whereYear('tanggal', '=', $tahun)
            ->groupBy(DB::raw("MONTHNAME(tanggal)"))
            ->pluck('totaljual', 'bulan'); */
        $sales = Sales::getPenjualanByPeriodeChart("bulanan", $tahun);

        //SALES RETURN
        /*         $returSales = Sales::select(DB::raw("SUM(total) as totalretur"), DB::raw("MONTHNAME(tanggal) as bulan"))
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('trjuald')
                    ->whereColumn('trjualh.entiti', 'trjuald.entiti')
                    ->whereColumn('trjualh.noinvoice', 'trjuald.noinvoice')
                    ->where('trjuald.faktorqty', '=', 1);
            })
            ->whereYear('tanggal', '=', $tahun)
            ->groupBy(DB::raw("MONTHNAME(tanggal)"))
            ->pluck('totalretur', 'bulan'); */
        $returSales = Sales::getReturPenjualanByPeriodeChart("bulanan", $tahun);

        foreach (Helper::$months as $bulan) {
            $sales[$bulan] = $sales[$bulan] ?? 0;
            $returSales[$bulan] = $returSales[$bulan] ?? 0;
            $netSales[$bulan] = $sales[$bulan] - $returSales[$bulan];
        }
        $netSales = collect((object)$netSales);

        $ajaxData['labels'] = $netSales->keys();
        $ajaxData['data'] = $netSales->values();

        return response()->json(
            array(
                'status' => 'ok',
                'msg' => $ajaxData
            ),
            200
        );
    }

    public function refreshProfitLossChart(Request $request)
    {
        // PROFIT AND LOSS
        $tahun = $request->get('tahun');
        // $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        //SALES
        /*         $sales = Sales::select(DB::raw("SUM(total) as totaljual"), DB::raw("MONTHNAME(tanggal) as bulan"))
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('trjuald')
                    ->whereColumn('trjualh.entiti', 'trjuald.entiti')
                    ->whereColumn('trjualh.noinvoice', 'trjuald.noinvoice')
                    ->where('trjuald.faktorqty', '=', -1);
            })
            ->whereYear('tanggal', '=', $tahun)
            ->groupBy(DB::raw("MONTHNAME(tanggal)"))
            ->pluck('totaljual', 'bulan'); */
        $sales = Sales::getPenjualanByPeriodeChart("bulanan", $tahun);

        //SALES RETURN
        /* $returSales = Sales::select(DB::raw("SUM(total) as totalretur"), DB::raw("MONTHNAME(tanggal) as bulan"))
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('trjuald')
                    ->whereColumn('trjualh.entiti', 'trjuald.entiti')
                    ->whereColumn('trjualh.noinvoice', 'trjuald.noinvoice')
                    ->where('trjuald.faktorqty', '=', 1);
            })
            ->whereYear('tanggal', '=', $tahun)
            ->groupBy(DB::raw("MONTHNAME(tanggal)"))
            ->pluck('totalretur', 'bulan'); */
        $returSales = Sales::getReturPenjualanByPeriodeChart("bulanan", $tahun);

        // dd($returSales);
        foreach (Helper::$months as $bulan) {
            $sales[$bulan] = $sales[$bulan] ?? 0;
            $returSales[$bulan] = $returSales[$bulan] ?? 0;
            $netSales[$bulan] = $sales[$bulan] - $returSales[$bulan];
        }
        $netSales = collect((object)$netSales);

        //HPP SALES
        /* $hppSales = StokBarang::join('trjualh', function ($join) {
            $join->on('trjualh.noinvoice', '=', 'stokbarang.kodereferensi');
            $join->on('trjualh.entiti', '=', 'stokbarang.entiti');
        })
            ->select(DB::raw("SUM(stokbarang.qty*stokbarang.hpp) as hppsales"), DB::raw("MONTHNAME(trjualh.tanggal) as bulan"))
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('trjuald')
                    ->whereColumn('trjualh.entiti', 'trjuald.entiti')
                    ->whereColumn('trjualh.noinvoice', 'trjuald.noinvoice')
                    ->where('trjuald.faktorqty', '=', -1);
            })
            ->whereYear('trjualh.tanggal', '=', $tahun)
            ->groupBy(DB::raw("MONTHNAME(trjualh.tanggal)"))
            ->pluck('hppsales', 'bulan'); */
        $hppSales = StokBarang::getHPPPenjualan("bulanan", $tahun);

        //HPP SALES RETUR
        /*         $hppReturSales = StokBarang::join('trjualh', function ($join) {
            $join->on('trjualh.noinvoice', '=', 'stokbarang.kodereferensi');
            $join->on('trjualh.entiti', '=', 'stokbarang.entiti');
        })
            ->select(DB::raw("SUM(stokbarang.qty*stokbarang.hpp) as hppretursales"), DB::raw("MONTHNAME(trjualh.tanggal) as bulan"))
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('trjuald')
                    ->whereColumn('trjualh.entiti', 'trjuald.entiti')
                    ->whereColumn('trjualh.noinvoice', 'trjuald.noinvoice')
                    ->where('trjuald.faktorqty', '=', 1);
            })
            ->whereYear('trjualh.tanggal', '=', $tahun)
            ->groupBy(DB::raw("MONTHNAME(trjualh.tanggal)"))
            ->pluck('hppretursales', 'bulan'); */
        $hppReturSales = StokBarang::getReturHPPPenjualan("bulanan", $tahun);

        foreach (Helper::$months as $bulan) {
            $hppSales[$bulan] = $hppSales[$bulan] ?? 0;
            $hppReturSales[$bulan] = $hppReturSales[$bulan] ?? 0;
            $hppNetSales[$bulan] = $hppSales[$bulan] - $hppReturSales[$bulan];
            $profit[$bulan] = (float)$netSales[$bulan] - (float)$hppNetSales[$bulan];
        }
        $profit = collect((object)$profit);

        $ajaxData['labels'] = $profit->keys();
        $ajaxData['data'] = $profit->values();

        return response()->json(
            array(
                'status' => 'ok',
                'msg' => $ajaxData
            ),
            200
        );
    }

    public function refreshBestsellerChart(Request $request)
    {
        //OBAT TERLARIS
        // $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        $kriteria = $request->get('kriteria');
        $isiFilter = $request->get('isiFilter');

        /* if ($kriteria == "tahun") {
            //SELECT (SUM(d.qty*d.faktorqty))*-1 as qtyterjual,d.namabarang FROM trjualh as h inner join trjuald as d on h.entiti=d.entiti and h.noinvoice=d.noinvoice WHERE MONTH(h.tanggal)='10' and year(h.tanggal)>='2022' GROUP BY d.namabarang ORDER BY qtyterjual DESC LIMIT 10;
            $bestSeller = Sales::join('trjuald', function ($join) {
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
        } elseif ($kriteria == "bulan") {
            $isiFilter = explode(" ", $isiFilter);
            foreach (Helper::$months as $key => $bulan) {
                if ($bulan == $isiFilter[0]) {
                    $isiFilter[0] = $key + 1;
                }
            }
            $bestSeller = Sales::join('trjuald', function ($join) {
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
            $bestSeller = Sales::join('trjuald', function ($join) {
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
        } */
        $bestSeller = Sales::getBestsellerByPeriodeChart($kriteria, $isiFilter);

        $ajaxData['labels'] = $bestSeller->keys();
        $ajaxData['data'] = $bestSeller->values();

        return response()->json(
            array(
                'status' => 'ok',
                'msg' => $ajaxData
            ),
            200
        );
        //=============================================================================================================
    }
}
