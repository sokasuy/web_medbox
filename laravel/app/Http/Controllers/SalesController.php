<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Illuminate\Http\Request;

use Carbon\Carbon;
use DateTime;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function show(Sales $sales)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function edit(Sales $sales)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sales $sales)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sales $sales)
    {
        //
    }

    public function reportPenjualan()
    {
        return view('reports.penjualan');
    }

    public function getReportPenjualan(Request $request)
    {
        // SELECT h.entiti,h.noinvoice,h.tanggal,h.pembayaran,d.sku,d.namabarang,d.qty,d.satuan,d.harga,d.jumlah,d.statusbarang,h.adddate,h.editdate FROM trjualh as h inner join trjuald as d on h.entiti=d.entiti and h.noinvoice=d.noinvoice WHERE h.tanggal='2022-11-06' and d.faktorqty=-1 ORDER BY h.adddate ASC, d.namabarang ASC;
        $kriteria = $request->get('kriteria');
        $isiFilter = $request->get('isiFilter');

        if ($kriteria == "hari_ini") {
            $data = Sales::select('trjualh.entiti', 'trjualh.noinvoice', 'trjualh.tanggal', 'trjualh.pembayaran', 'trjuald.sku', 'trjuald.namabarang', 'trjuald.qty', 'trjuald.satuan', 'trjuald.harga', 'trjuald.jumlah', 'trjuald.statusbarang', 'trjualh.adddate', 'trjualh.editdate')
                ->join('trjuald', function ($join) {
                    $join->on('trjuald.entiti', '=', 'trjualh.entiti');
                    $join->on('trjuald.noinvoice', '=', 'trjualh.noinvoice');
                })->where('trjualh.tanggal', '=', Carbon::now()->toDateString())
                ->where('trjuald.faktorqty', '=', -1)
                ->orderBy('trjualh.adddate')
                ->get();
        } else if ($kriteria == "3_hari") {
            $data = Sales::select('trjualh.entiti', 'trjualh.noinvoice', 'trjualh.tanggal', 'trjualh.pembayaran', 'trjuald.sku', 'trjuald.namabarang', 'trjuald.qty', 'trjuald.satuan', 'trjuald.harga', 'trjuald.jumlah', 'trjuald.statusbarang', 'trjualh.adddate', 'trjualh.editdate')
                ->join('trjuald', function ($join) {
                    $join->on('trjuald.entiti', '=', 'trjualh.entiti');
                    $join->on('trjuald.noinvoice', '=', 'trjualh.noinvoice');
                })->where('trjualh.tanggal', '>=', Carbon::now()->subDays(3)->toDateString())
                ->where('trjuald.faktorqty', '=', -1)
                ->orderBy('trjualh.adddate')
                ->get();
        } else if ($kriteria == "7_hari") {
            $data = Sales::select('trjualh.entiti', 'trjualh.noinvoice', 'trjualh.tanggal', 'trjualh.pembayaran', 'trjuald.sku', 'trjuald.namabarang', 'trjuald.qty', 'trjuald.satuan', 'trjuald.harga', 'trjuald.jumlah', 'trjuald.statusbarang', 'trjualh.adddate', 'trjualh.editdate')
                ->join('trjuald', function ($join) {
                    $join->on('trjuald.entiti', '=', 'trjualh.entiti');
                    $join->on('trjuald.noinvoice', '=', 'trjualh.noinvoice');
                })->where('trjualh.tanggal', '>=', Carbon::now()->subDays(7)->toDateString())
                ->where('trjuald.faktorqty', '=', -1)
                ->orderBy('trjualh.adddate')
                ->get();
        } else if ($kriteria == "14_hari") {
            $data = Sales::select('trjualh.entiti', 'trjualh.noinvoice', 'trjualh.tanggal', 'trjualh.pembayaran', 'trjuald.sku', 'trjuald.namabarang', 'trjuald.qty', 'trjuald.satuan', 'trjuald.harga', 'trjuald.jumlah', 'trjuald.statusbarang', 'trjualh.adddate', 'trjualh.editdate')
                ->join('trjuald', function ($join) {
                    $join->on('trjuald.entiti', '=', 'trjualh.entiti');
                    $join->on('trjuald.noinvoice', '=', 'trjualh.noinvoice');
                })->where('trjualh.tanggal', '>=', Carbon::now()->subDays(14)->toDateString())
                ->where('trjuald.faktorqty', '=', -1)
                ->orderBy('trjualh.adddate')
                ->get();
        } else if ($kriteria == "bulan_berjalan") {
            $data = Sales::select('trjualh.entiti', 'trjualh.noinvoice', 'trjualh.tanggal', 'trjualh.pembayaran', 'trjuald.sku', 'trjuald.namabarang', 'trjuald.qty', 'trjuald.satuan', 'trjuald.harga', 'trjuald.jumlah', 'trjuald.statusbarang', 'trjualh.adddate', 'trjualh.editdate')
                ->join('trjuald', function ($join) {
                    $join->on('trjuald.entiti', '=', 'trjualh.entiti');
                    $join->on('trjuald.noinvoice', '=', 'trjualh.noinvoice');
                })->whereYear('trjualh.tanggal', '=', Carbon::now()->year)
                ->whereMonth('trjualh.tanggal', '=', Carbon::now()->month)
                ->where('trjuald.faktorqty', '=', -1)
                ->orderBy('trjualh.adddate')
                ->get();
        } else if ($kriteria == "semua") {
            $data = Sales::select('trjualh.entiti', 'trjualh.noinvoice', 'trjualh.tanggal', 'trjualh.pembayaran', 'trjuald.sku', 'trjuald.namabarang', 'trjuald.qty', 'trjuald.satuan', 'trjuald.harga', 'trjuald.jumlah', 'trjuald.statusbarang', 'trjualh.adddate', 'trjualh.editdate')
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

            $data = Sales::select('trjualh.entiti', 'trjualh.noinvoice', 'trjualh.tanggal', 'trjualh.pembayaran', 'trjuald.sku', 'trjuald.namabarang', 'trjuald.qty', 'trjuald.satuan', 'trjuald.harga', 'trjuald.jumlah', 'trjuald.statusbarang', 'trjualh.adddate', 'trjualh.editdate')
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

        return response()->json(
            array(
                'status' => 'ok',
                'data' => $data
            ),
            200
        );
    }
}
