<?php

namespace App\Http\Controllers;

use App\Models\StokBarang;
use Illuminate\Http\Request;

use DB;
use Carbon\Carbon;

class StokBarangController extends Controller
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
     * @param  \App\Models\StokBarang  $stokBarang
     * @return \Illuminate\Http\Response
     */
    public function show(StokBarang $stokBarang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StokBarang  $stokBarang
     * @return \Illuminate\Http\Response
     */
    public function edit(StokBarang $stokBarang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StokBarang  $stokBarang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StokBarang $stokBarang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StokBarang  $stokBarang
     * @return \Illuminate\Http\Response
     */
    public function destroy(StokBarang $stokBarang)
    {
        //
    }

    public function indexExpiryDate()
    {
        return view('reports.expirydate');
    }

    public function getExpiryDate()
    {
        //
        // SELECT s.entiti,b.sku,b.namabarang,Sum(s.qty) as jumlah,b.satk,b.golongan,b.kategori,s.nobatch,s.ed,b.pabrik,b.jenis,b.discontinue FROM msbarang as b inner join stokbarang as s on b.entiti=s.entiti and b.sku=s.sku GROUP BY s.entiti,b.sku,b.namabarang,b.satk,b.golongan,b.kategori,s.nobatch,s.ed,b.pabrik,b.jenis,b.discontinue ORDER BY s.ed ASC;
        $data = StokBarang::select('stokbarang.entiti', 'msbarang.sku', 'msbarang.namabarang', DB::raw('Sum(stokbarang.qty) as jumlah'), 'msbarang.satk', 'msbarang.golongan', 'msbarang.kategori', 'stokbarang.nobatch', 'stokbarang.ed', DB::raw('DATEDIFF(stokbarang.ed,now()) AS harimenujuexpired'), 'msbarang.pabrik', 'msbarang.jenis', 'msbarang.discontinue')
            ->join('msbarang', function ($join) {
                $join->on('msbarang.entiti', '=', 'stokbarang.entiti');
                $join->on('msbarang.sku', '=', 'stokbarang.sku');
            })
            ->groupBy('stokbarang.entiti', 'msbarang.sku', 'msbarang.namabarang', 'msbarang.satk', 'msbarang.golongan', 'msbarang.kategori', 'stokbarang.nobatch', 'stokbarang.ed', 'msbarang.pabrik', 'msbarang.jenis', 'msbarang.discontinue')
            ->orderBy('stokbarang.ed')->orderByDesc('jumlah')
            ->get();
        $json_data['data'] = $data;
        // return view('reports.expirydate', compact('data'));
        return response()->json(
            array(
                'status' => 'ok',
                'data' => $data
            ),
            200
        );
    }

    public function refreshExpiryDate(Request $request)
    {
        $kriteria = $request->get('kriteria');
        $isiFilter = $request->get('isiFilter');

        if ($kriteria == "sudah_expired") {
            $data = StokBarang::select('stokbarang.entiti', 'msbarang.sku', 'msbarang.namabarang', DB::raw('Sum(stokbarang.qty) as jumlah'), 'msbarang.satk', 'msbarang.golongan', 'msbarang.kategori', 'stokbarang.nobatch', 'stokbarang.ed', DB::raw('DATEDIFF(stokbarang.ed,now()) AS harimenujuexpired'), 'msbarang.pabrik', 'msbarang.jenis', 'msbarang.discontinue')
                ->join('msbarang', function ($join) {
                    $join->on('msbarang.entiti', '=', 'stokbarang.entiti');
                    $join->on('msbarang.sku', '=', 'stokbarang.sku');
                })
                ->where('stokbarang.ed', '<=', Carbon::now()->toDateString())
                ->groupBy('stokbarang.entiti', 'msbarang.sku', 'msbarang.namabarang', 'msbarang.satk', 'msbarang.golongan', 'msbarang.kategori', 'stokbarang.nobatch', 'stokbarang.ed', 'msbarang.pabrik', 'msbarang.jenis', 'msbarang.discontinue')
                ->orderBy('stokbarang.ed')->orderByDesc('jumlah')
                ->get();
        }
        $json_data['data'] = $data;
        return response()->json(
            array(
                'status' => 'ok',
                'msg' => $json_data
            ),
            200
        );
    }
}
