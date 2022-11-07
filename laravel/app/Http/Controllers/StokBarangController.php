<?php

namespace App\Http\Controllers;

use App\Models\StokBarang;
use Illuminate\Http\Request;

use DB;
use Carbon\Carbon;
use DateTime;

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

    public function reportExpiryDate()
    {
        return view('reports.expirydate');
    }

    public function getReportExpiryDate(Request $request)
    {
        //
        // SELECT s.entiti,b.sku,b.namabarang,Sum(s.qty) as jumlah,b.satk,b.golongan,b.kategori,s.nobatch,s.ed,b.pabrik,b.jenis,b.discontinue FROM msbarang as b inner join stokbarang as s on b.entiti=s.entiti and b.sku=s.sku GROUP BY s.entiti,b.sku,b.namabarang,b.satk,b.golongan,b.kategori,s.nobatch,s.ed,b.pabrik,b.jenis,b.discontinue ORDER BY s.ed ASC;
        // dd($kriteria);
        $kriteria = $request->get('kriteria');
        $isiFilter = $request->get('isiFilter');

        if ($kriteria == "semua") {
            $data = StokBarang::select('stokbarang.entiti', 'msbarang.sku', 'msbarang.namabarang', DB::raw('Sum(stokbarang.qty) as jumlah'), 'msbarang.satk', 'msbarang.golongan', 'msbarang.kategori', 'stokbarang.nobatch', 'stokbarang.ed', DB::raw('DATEDIFF(stokbarang.ed,now()) AS harimenujuexpired'), 'msbarang.pabrik', 'msbarang.jenis', 'msbarang.discontinue')
                ->join('msbarang', function ($join) {
                    $join->on('msbarang.entiti', '=', 'stokbarang.entiti');
                    $join->on('msbarang.sku', '=', 'stokbarang.sku');
                })
                ->groupBy('stokbarang.entiti', 'msbarang.sku', 'msbarang.namabarang', 'msbarang.satk', 'msbarang.golongan', 'msbarang.kategori', 'stokbarang.nobatch', 'stokbarang.ed', 'msbarang.pabrik', 'msbarang.jenis', 'msbarang.discontinue')
                ->orderBy('stokbarang.ed')->orderByDesc('jumlah')
                ->get();
        } elseif ($kriteria == "sudah_expired") {
            $data = StokBarang::select('stokbarang.entiti', 'msbarang.sku', 'msbarang.namabarang', DB::raw('Sum(stokbarang.qty) as jumlah'), 'msbarang.satk', 'msbarang.golongan', 'msbarang.kategori', 'stokbarang.nobatch', 'stokbarang.ed', DB::raw('DATEDIFF(stokbarang.ed,now()) AS harimenujuexpired'), 'msbarang.pabrik', 'msbarang.jenis', 'msbarang.discontinue')
                ->join('msbarang', function ($join) {
                    $join->on('msbarang.entiti', '=', 'stokbarang.entiti');
                    $join->on('msbarang.sku', '=', 'stokbarang.sku');
                })
                ->where('stokbarang.ed', '<', Carbon::now()->toDateString())
                ->groupBy('stokbarang.entiti', 'msbarang.sku', 'msbarang.namabarang', 'msbarang.satk', 'msbarang.golongan', 'msbarang.kategori', 'stokbarang.nobatch', 'stokbarang.ed', 'msbarang.pabrik', 'msbarang.jenis', 'msbarang.discontinue')
                ->orderBy('stokbarang.ed')->orderByDesc('jumlah')
                ->get();
        } elseif ($kriteria == "30_hari_sebelum_expired") {
            $data = StokBarang::select('stokbarang.entiti', 'msbarang.sku', 'msbarang.namabarang', DB::raw('Sum(stokbarang.qty) as jumlah'), 'msbarang.satk', 'msbarang.golongan', 'msbarang.kategori', 'stokbarang.nobatch', 'stokbarang.ed', DB::raw('DATEDIFF(stokbarang.ed,now()) AS harimenujuexpired'), 'msbarang.pabrik', 'msbarang.jenis', 'msbarang.discontinue')
                ->join('msbarang', function ($join) {
                    $join->on('msbarang.entiti', '=', 'stokbarang.entiti');
                    $join->on('msbarang.sku', '=', 'stokbarang.sku');
                })
                ->where('stokbarang.ed', '>=', Carbon::now()->toDateString())
                ->where('stokbarang.ed', '<=', Carbon::now()->addDays(30)->toDateString())
                ->groupBy('stokbarang.entiti', 'msbarang.sku', 'msbarang.namabarang', 'msbarang.satk', 'msbarang.golongan', 'msbarang.kategori', 'stokbarang.nobatch', 'stokbarang.ed', 'msbarang.pabrik', 'msbarang.jenis', 'msbarang.discontinue')
                ->orderBy('stokbarang.ed')->orderByDesc('jumlah')
                ->get();
        } elseif ($kriteria == "15_hari_sebelum_expired") {
            $data = StokBarang::select('stokbarang.entiti', 'msbarang.sku', 'msbarang.namabarang', DB::raw('Sum(stokbarang.qty) as jumlah'), 'msbarang.satk', 'msbarang.golongan', 'msbarang.kategori', 'stokbarang.nobatch', 'stokbarang.ed', DB::raw('DATEDIFF(stokbarang.ed,now()) AS harimenujuexpired'), 'msbarang.pabrik', 'msbarang.jenis', 'msbarang.discontinue')
                ->join('msbarang', function ($join) {
                    $join->on('msbarang.entiti', '=', 'stokbarang.entiti');
                    $join->on('msbarang.sku', '=', 'stokbarang.sku');
                })
                ->where('stokbarang.ed', '>=', Carbon::now()->toDateString())
                ->where('stokbarang.ed', '<=', Carbon::now()->addDays(15)->toDateString())
                ->groupBy('stokbarang.entiti', 'msbarang.sku', 'msbarang.namabarang', 'msbarang.satk', 'msbarang.golongan', 'msbarang.kategori', 'stokbarang.nobatch', 'stokbarang.ed', 'msbarang.pabrik', 'msbarang.jenis', 'msbarang.discontinue')
                ->orderBy('stokbarang.ed')->orderByDesc('jumlah')
                ->get();
        } elseif ($kriteria == "7_hari_sebelum_expired") {
            $data = StokBarang::select('stokbarang.entiti', 'msbarang.sku', 'msbarang.namabarang', DB::raw('Sum(stokbarang.qty) as jumlah'), 'msbarang.satk', 'msbarang.golongan', 'msbarang.kategori', 'stokbarang.nobatch', 'stokbarang.ed', DB::raw('DATEDIFF(stokbarang.ed,now()) AS harimenujuexpired'), 'msbarang.pabrik', 'msbarang.jenis', 'msbarang.discontinue')
                ->join('msbarang', function ($join) {
                    $join->on('msbarang.entiti', '=', 'stokbarang.entiti');
                    $join->on('msbarang.sku', '=', 'stokbarang.sku');
                })
                ->where('stokbarang.ed', '>=', Carbon::now()->toDateString())
                ->where('stokbarang.ed', '<=', Carbon::now()->addDays(7)->toDateString())
                ->groupBy('stokbarang.entiti', 'msbarang.sku', 'msbarang.namabarang', 'msbarang.satk', 'msbarang.golongan', 'msbarang.kategori', 'stokbarang.nobatch', 'stokbarang.ed', 'msbarang.pabrik', 'msbarang.jenis', 'msbarang.discontinue')
                ->orderBy('stokbarang.ed')->orderByDesc('jumlah')
                ->get();
        } elseif ($kriteria == "berdasarkan_tanggal_expired") {
            $isiFilter = explode(" - ", $isiFilter);
            $isiFilter[0] = explode("/", $isiFilter[0]);
            $isiFilter[1] = explode("/", $isiFilter[1]);
            $begin = new DateTime($isiFilter[0][2] . "-" . $isiFilter[0][0] . "-" . $isiFilter[0][1]);
            $end = new DateTime($isiFilter[1][2] . "-" . $isiFilter[1][0] . "-" . $isiFilter[1][1]);

            $data = StokBarang::select('stokbarang.entiti', 'msbarang.sku', 'msbarang.namabarang', DB::raw('Sum(stokbarang.qty) as jumlah'), 'msbarang.satk', 'msbarang.golongan', 'msbarang.kategori', 'stokbarang.nobatch', 'stokbarang.ed', DB::raw('DATEDIFF(stokbarang.ed,now()) AS harimenujuexpired'), 'msbarang.pabrik', 'msbarang.jenis', 'msbarang.discontinue')
                ->join('msbarang', function ($join) {
                    $join->on('msbarang.entiti', '=', 'stokbarang.entiti');
                    $join->on('msbarang.sku', '=', 'stokbarang.sku');
                })
                ->where('stokbarang.ed', '>=', $begin)
                ->where('stokbarang.ed', '<=', $end)
                ->groupBy('stokbarang.entiti', 'msbarang.sku', 'msbarang.namabarang', 'msbarang.satk', 'msbarang.golongan', 'msbarang.kategori', 'stokbarang.nobatch', 'stokbarang.ed', 'msbarang.pabrik', 'msbarang.jenis', 'msbarang.discontinue')
                ->orderBy('stokbarang.ed')->orderByDesc('jumlah')
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
