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
        $grupmember = Sales::getGrupMember();
        return view('reports.penjualan', compact('grupmember'));
    }

    public function getReportPenjualan(Request $request)
    {
        // SELECT h.entiti,h.noinvoice,h.tanggal,h.pembayaran,d.sku,d.namabarang,d.qty,d.satuan,d.harga,d.jumlah,d.statusbarang,h.adddate,h.editdate FROM trjualh as h inner join trjuald as d on h.entiti=d.entiti and h.noinvoice=d.noinvoice WHERE h.tanggal='2022-11-06' and d.faktorqty=-1 ORDER BY h.adddate ASC, d.namabarang ASC;
        $kriteriaPeriode = $request->get('kriteriaPeriode');
        $isiFilterPeriode = $request->get('isiFilterPeriode');
        $isiFilterGrupMember = $request->get('isiFilterGrupMember');

        if ($kriteriaPeriode == "hari_ini") {
            // $data = Sales::getPenjualanByPeriode($kriteria, Carbon::now()->toDateString());
            $isiFilterPeriode  = Carbon::now()->toDateString();
        } else if ($kriteriaPeriode == "3_hari") {
            // $data = Sales::getPenjualanByPeriode($kriteria, Carbon::now()->subDays(3)->toDateString());
            $isiFilterPeriode  = Carbon::now()->subDays(3)->toDateString();
        } else if ($kriteriaPeriode == "7_hari") {
            // $data = Sales::getPenjualanByPeriode($kriteria, Carbon::now()->subDays(7)->toDateString());
            $isiFilterPeriode  = Carbon::now()->subDays(7)->toDateString();
        } else if ($kriteriaPeriode == "14_hari") {
            // $data = Sales::getPenjualanByPeriode($kriteria, Carbon::now()->subDays(14)->toDateString());
            $isiFilterPeriode  = Carbon::now()->subDays(14)->toDateString();
        } else if ($kriteriaPeriode == "bulan_berjalan") {
            // $data = Sales::getPenjualanByPeriode($kriteria, Carbon::now());
            $isiFilterPeriode  = Carbon::now();
        } else if ($kriteriaPeriode == "semua") {
            // $data = Sales::getPenjualanByPeriode($kriteria, $isiFilter);
        } else if ($kriteriaPeriode == "berdasarkan_tanggal_penjualan") {
            // $data = Sales::getPenjualanByPeriode($kriteria, $isiFilter);
        }

        $data = Sales::getPenjualanByPeriode($kriteriaPeriode, $isiFilterPeriode, $isiFilterGrupMember);
        return response()->json(
            array(
                'status' => 'ok',
                'data' => $data
            ),
            200
        );
    }
}
