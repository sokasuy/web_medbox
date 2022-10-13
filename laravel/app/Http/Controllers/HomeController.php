<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Sales;
use App\Models\StokBarang;
use App\Models\MsKontak;
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

        //=============================================================================================================
        // COMBO BOX DI DASHBOARD
        $dataSupplier = MsKontak::select('kodekontak', 'perusahaan')->where('jeniskontak', '=', 'SUPPLIER')->orderBy('perusahaan')->get();
        $dataCbo['dataSupplier'] = $dataSupplier;

        $dataTahunPembelian = Purchase::select(DB::raw("YEAR(tanggal) as tahun"))->groupBy(DB::raw("YEAR(tanggal)"))->orderBy(DB::raw("YEAR(tanggal)"))->get();
        $dataCbo['tahunPembelian'] = $dataTahunPembelian;

        $dataTahunPenjualan = Sales::select(DB::raw("YEAR(tanggal) as tahun"))->groupBy(DB::raw("YEAR(tanggal)"))->orderBy(DB::raw("YEAR(tanggal)"))->get();
        $dataCbo['tahunPenjualan'] = $dataTahunPenjualan;
        //=============================================================================================================

        // DB::enableQueryLog();
        //=============================================================================================================
        //CHARTS
        //PURCHASING
        //SELECT SUM(h.subtotal) as totalbeli,MONTHNAME(h.tanggal) as bulan FROM trterimah as h WHERE EXISTS(SELECT 1 FROM trterimad WHERE entiti=h.entiti and noterima=h.noterima and faktorqty=1) AND year(tanggal)>='2022' GROUP BY MONTHNAME(tanggal);
        $purchase = Purchase::select(DB::raw("SUM(subtotal) as totalbeli"), DB::raw("MONTHNAME(tanggal) as bulan"))
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('trterimad')
                    ->whereColumn('trterimah.entiti', 'trterimad.entiti')
                    ->whereColumn('trterimah.noterima', 'trterimad.noterima')
                    ->where('trterimad.faktorqty', '=', 1);
            })
            ->whereYear('tanggal', '=', Carbon::now()->format('Y'))
            ->groupBy(DB::raw("MONTHNAME(tanggal)"))
            ->pluck('totalbeli', 'bulan');
        // dd(DB::getQueryLog());

        //PURCHASE RETURN
        //SELECT SUM(h.subtotal) as totalbeli,MONTHNAME(h.tanggal) as bulan FROM trterimah as h WHERE EXISTS(SELECT 1 FROM trterimad WHERE entiti=h.entiti and noterima=h.noterima and faktorqty=-1) AND year(tanggal)>='2022' GROUP BY MONTHNAME(tanggal);
        $returPurchase = Purchase::select(DB::raw("SUM(subtotal) as totalretur"), DB::raw("MONTHNAME(tanggal) as bulan"))
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('trterimad')
                    ->whereColumn('trterimah.entiti', 'trterimad.entiti')
                    ->whereColumn('trterimah.noterima', 'trterimad.noterima')
                    ->where('trterimad.faktorqty', '=', -1);
            })
            ->whereYear('tanggal', '=', Carbon::now()->format('Y'))
            ->groupBy(DB::raw("MONTHNAME(tanggal)"))
            ->pluck('totalretur', 'bulan');

        foreach ($months as $bulan) {
            if (($purchase[$bulan]) ?? null) {
                $beli[$bulan] = $purchase[$bulan];
            } else {
                $beli[$bulan] = 0;
            }

            if (($returPurchase[$bulan]) ?? null) {
                $kembalikanBeli[$bulan] = $returPurchase[$bulan];
            } else {
                $kembalikanBeli[$bulan] = 0;
            }
            $beli[$bulan] = $beli[$bulan] - $kembalikanBeli[$bulan];
        }
        $beli = collect((object)$beli);
        $labels['purchase'] = $beli->keys();
        $data['purchase'] = $beli->values();
        //=============================================================================================================

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

        // $object = json_encode($beli);
        // $beli = json_decode(json_encode($object));

        //=============================================================================================================
        //SALES
        //SELECT SUM(h.total) as totaljual,MONTHNAME(h.tanggal) as bulan FROM trjualh as h WHERE EXISTS(SELECT 1 FROM trjuald WHERE entiti=h.entiti and noinvoice=h.noinvoice and faktorqty=-1) AND year(h.tanggal)>='2022' GROUP BY MONTHNAME(h.tanggal);
        $sales = Sales::select(DB::raw("SUM(total) as totaljual"), DB::raw("MONTHNAME(tanggal) as bulan"))
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('trjuald')
                    ->whereColumn('trjualh.entiti', 'trjuald.entiti')
                    ->whereColumn('trjualh.noinvoice', 'trjuald.noinvoice')
                    ->where('trjuald.faktorqty', '=', -1);
            })
            ->whereYear('tanggal', '=', Carbon::now()->format('Y'))
            ->groupBy(DB::raw("MONTHNAME(tanggal)"))
            ->pluck('totaljual', 'bulan');

        //SALES RETURN
        $returSales = Sales::select(DB::raw("SUM(total) as totalretur"), DB::raw("MONTHNAME(tanggal) as bulan"))
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('trjuald')
                    ->whereColumn('trjualh.entiti', 'trjuald.entiti')
                    ->whereColumn('trjualh.noinvoice', 'trjuald.noinvoice')
                    ->where('trjuald.faktorqty', '=', 1);
            })
            ->whereYear('tanggal', '=', Carbon::now()->format('Y'))
            ->groupBy(DB::raw("MONTHNAME(tanggal)"))
            ->pluck('totalretur', 'bulan');

        foreach ($months as $bulan) {
            if (($sales[$bulan]) ?? null) {
                $jual[$bulan] = $sales[$bulan];
            } else {
                $jual[$bulan] = 0;
            }
            if (($returSales[$bulan]) ?? null) {
                $kembalikanJual[$bulan] = $returSales[$bulan];
            } else {
                $kembalikanJual[$bulan] = 0;
            }
            $jual[$bulan] = $jual[$bulan] - $kembalikanJual[$bulan];
        }
        $jual = collect((object)$jual);

        $labels['sales'] = $jual->keys();
        $data['sales'] = $jual->values();
        //=============================================================================================================

        //=============================================================================================================
        // PROFIT AND LOSS
        //HPP SALES
        //SELECT SUM(s.qty*s.hpp) as hpp,MONTHNAME(h.tanggal) as bulan FROM trjualh as h inner join stokbarang as s on h.noinvoice=s.kodereferensi and h.entiti=s.entiti WHERE EXISTS(SELECT 1 FROM trjuald WHERE entiti=h.entiti and noinvoice=h.noinvoice and faktorqty=-1) AND year(h.tanggal)>='2022' GROUP BY MONTHNAME(h.tanggal);
        $hppSales = StokBarang::join('trjualh', function ($join) {
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
            ->whereYear('trjualh.tanggal', '=', Carbon::now()->format('Y'))
            ->groupBy(DB::raw("MONTHNAME(trjualh.tanggal)"))
            ->pluck('hppsales', 'bulan');

        $hppSalesRetur = StokBarang::join('trjualh', function ($join) {
            $join->on('trjualh.noinvoice', '=', 'stokbarang.kodereferensi');
            $join->on('trjualh.entiti', '=', 'stokbarang.entiti');
        })
            ->select(DB::raw("SUM(stokbarang.qty*stokbarang.hpp) as hppsalesretur"), DB::raw("MONTHNAME(trjualh.tanggal) as bulan"))
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('trjuald')
                    ->whereColumn('trjualh.entiti', 'trjuald.entiti')
                    ->whereColumn('trjualh.noinvoice', 'trjuald.noinvoice')
                    ->where('trjuald.faktorqty', '=', 1);
            })
            ->whereYear('trjualh.tanggal', '=', Carbon::now()->format('Y'))
            ->groupBy(DB::raw("MONTHNAME(trjualh.tanggal)"))
            ->pluck('hppsalesretur', 'bulan');
        // dd(DB::getQueryLog());

        foreach ($months as $bulan) {
            if (($hppSales[$bulan]) ?? null) {
                $hppJual[$bulan] = $hppSales[$bulan];
            } else {
                $hppJual[$bulan] = 0;
            }
            if (($returSales[$bulan]) ?? null) {
                $hppKembalikanJual[$bulan] = $hppSalesRetur[$bulan];
            } else {
                $hppKembalikanJual[$bulan] = 0;
            }
            $hppNet[$bulan] = $hppJual[$bulan] - $hppKembalikanJual[$bulan];
            $profit[$bulan] = (float)$jual[$bulan] - (float)$hppNet[$bulan];
        }
        $profit = collect((object)$profit);

        $labels['profit'] = $profit->keys();
        $data['profit'] = $profit->values();
        //=============================================================================================================

        //=============================================================================================================
        //OBAT TERLARIS
        //SELECT (SUM(d.qty*d.faktorqty))*-1 as qtyterjual,d.namabarang FROM trjualh as h inner join trjuald as d on h.entiti=d.entiti and h.noinvoice=d.noinvoice WHERE MONTH(h.tanggal)='10' and year(h.tanggal)>='2022' GROUP BY d.namabarang ORDER BY qtyterjual DESC LIMIT 10;
        $bestSeller = Sales::join('trjuald', function ($join) {
            $join->on('trjualh.noinvoice', '=', 'trjuald.noinvoice');
            $join->on('trjualh.entiti', '=', 'trjuald.entiti');
        })
            ->select(DB::raw("(SUM(trjuald.qty*trjuald.faktorqty))*-1 as qtyterjual"), 'namabarang')
            ->whereYear('trjualh.tanggal', '=', Carbon::now()->format('Y'))
            ->groupBy('namabarang')
            ->OrderByDesc(DB::raw("qtyterjual"))
            ->Limit(10)
            ->pluck('qtyterjual', 'namabarang');

        // dd(DB::getQueryLog());
        $labels['bestseller'] = $bestSeller->keys();
        $data['bestseller'] = $bestSeller->values();
        //=============================================================================================================

        return view('home', compact('labels', 'data', 'dataCbo'));
    }

    public function refreshPurchaseChart(Request $request)
    {
        $supplier = $request->get('supplier');
        $tahun = $request->get('tahun');

        $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        if ($supplier == "SEMUA") {
            //PURCHASING
            $purchase = Purchase::select(DB::raw("SUM(subtotal) as totalbeli"), DB::raw("MONTHNAME(tanggal) as bulan"))
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('trterimad')
                        ->whereColumn('trterimah.entiti', 'trterimad.entiti')
                        ->whereColumn('trterimah.noterima', 'trterimad.noterima')
                        ->where('trterimad.faktorqty', '=', 1);
                })
                ->whereYear('tanggal', '=', $tahun)
                ->groupBy(DB::raw("MONTHNAME(tanggal)"))
                ->pluck('totalbeli', 'bulan');

            //PURCHASE RETURN
            //SELECT SUM(h.subtotal) as totalbeli,MONTHNAME(h.tanggal) as bulan FROM trterimah as h WHERE EXISTS(SELECT 1 FROM trterimad WHERE entiti=h.entiti and noterima=h.noterima and faktorqty=-1) AND year(tanggal)>='2022' GROUP BY MONTHNAME(tanggal);
            $returPurchase = Purchase::select(DB::raw("SUM(subtotal) as totalretur"), DB::raw("MONTHNAME(tanggal) as bulan"))
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('trterimad')
                        ->whereColumn('trterimah.entiti', 'trterimad.entiti')
                        ->whereColumn('trterimah.noterima', 'trterimad.noterima')
                        ->where('trterimad.faktorqty', '=', -1);
                })
                ->whereYear('tanggal', '=', $tahun)
                ->groupBy(DB::raw("MONTHNAME(tanggal)"))
                ->pluck('totalretur', 'bulan');
        } else {
            //PURCHASING
            $purchase = Purchase::select(DB::raw("SUM(subtotal) as totalbeli"), DB::raw("MONTHNAME(tanggal) as bulan"))
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
                ->pluck('totalbeli', 'bulan');

            //PURCHASE RETURN
            //SELECT SUM(h.subtotal) as totalbeli,MONTHNAME(h.tanggal) as bulan FROM trterimah as h WHERE EXISTS(SELECT 1 FROM trterimad WHERE entiti=h.entiti and noterima=h.noterima and faktorqty=-1) AND year(tanggal)>='2022' GROUP BY MONTHNAME(tanggal);
            $returPurchase = Purchase::select(DB::raw("SUM(subtotal) as totalretur"), DB::raw("MONTHNAME(tanggal) as bulan"))
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
                ->pluck('totalretur', 'bulan');
        }

        foreach ($months as $bulan) {
            if (($purchase[$bulan]) ?? null) {
                $beli[$bulan] = $purchase[$bulan];
            } else {
                $beli[$bulan] = 0;
            }

            if (($returPurchase[$bulan]) ?? null) {
                $kembalikanBeli[$bulan] = $returPurchase[$bulan];
            } else {
                $kembalikanBeli[$bulan] = 0;
            }
            $beli[$bulan] = $beli[$bulan] - $kembalikanBeli[$bulan];
        }
        $beli = collect((object)$beli);

        $ajaxData['labels'] = $beli->keys();
        $ajaxData['data'] = $beli->values();

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

        $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        $sales = Sales::select(DB::raw("SUM(total) as totaljual"), DB::raw("MONTHNAME(tanggal) as bulan"))
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('trjuald')
                    ->whereColumn('trjualh.entiti', 'trjuald.entiti')
                    ->whereColumn('trjualh.noinvoice', 'trjuald.noinvoice')
                    ->where('trjuald.faktorqty', '=', -1);
            })
            ->whereYear('tanggal', '=', $tahun)
            ->groupBy(DB::raw("MONTHNAME(tanggal)"))
            ->pluck('totaljual', 'bulan');

        //SALES RETURN
        $returSales = Sales::select(DB::raw("SUM(total) as totalretur"), DB::raw("MONTHNAME(tanggal) as bulan"))
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('trjuald')
                    ->whereColumn('trjualh.entiti', 'trjuald.entiti')
                    ->whereColumn('trjualh.noinvoice', 'trjuald.noinvoice')
                    ->where('trjuald.faktorqty', '=', 1);
            })
            ->whereYear('tanggal', '=', $tahun)
            ->groupBy(DB::raw("MONTHNAME(tanggal)"))
            ->pluck('totalretur', 'bulan');

        foreach ($months as $bulan) {
            if (($sales[$bulan]) ?? null) {
                $jual[$bulan] = $sales[$bulan];
            } else {
                $jual[$bulan] = 0;
            }
            if (($returSales[$bulan]) ?? null) {
                $kembalikanJual[$bulan] = $returSales[$bulan];
            } else {
                $kembalikanJual[$bulan] = 0;
            }
            $jual[$bulan] = $jual[$bulan] - $kembalikanJual[$bulan];
        }
        $jual = collect((object)$jual);

        $ajaxData['labels'] = $jual->keys();
        $ajaxData['data'] = $jual->values();

        return response()->json(
            array(
                'status' => 'ok',
                'msg' => $ajaxData
            ),
            200
        );
    }
}
