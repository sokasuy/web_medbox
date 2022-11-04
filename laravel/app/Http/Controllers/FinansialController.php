<?php

namespace App\Http\Controllers;

use App\Models\Finansial;
use Illuminate\Http\Request;

use DB;

class FinansialController extends Controller
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
     * @param  \App\Models\Finansial  $finansial
     * @return \Illuminate\Http\Response
     */
    public function show(Finansial $finansial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Finansial  $finansial
     * @return \Illuminate\Http\Response
     */
    public function edit(Finansial $finansial)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Finansial  $finansial
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Finansial $finansial)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Finansial  $finansial
     * @return \Illuminate\Http\Response
     */
    public function destroy(Finansial $finansial)
    {
        //
    }
    public function reportHutangPiutang()
    {
        return view('reports.hutangpiutang');
    }

    public function getHutangPiutang()
    {
        //
        //SELECT f.entiti,f.kodetransaksi,f.tanggal,h.nofaktur,h.tglfaktur,f.kodekontak,k.perusahaan,(f.jumlah*f.faktorqty) as hutang,h.jangkawaktu,DATE_ADD(h.tglfaktur, INTERVAL h.jangkawaktu DAY) as tgljatuhtempo FROM trfinansial as f inner join mskontak as k on f.kodekontak=k.kodekontak and f.entiti=k.entiti inner join trterimah as h on f.kodereferensi=h.nofaktur and f.entiti=h.entiti WHERE f.formid='PENERIMAAN' and f.grup='HUTANG' GROUP BY f.entiti,f.kodetransaksi,f.tanggal,h.nofaktur,h.tglfaktur,f.kodekontak,k.perusahaan,h.jangkawaktu ORDER BY tgljatuhtempo ASC;
        $data = Finansial::select('trfinansial.entiti', 'trfinansial.kodetransaksi', 'trfinansial.tanggal', 'trterimah.nofaktur', 'trterimah.tglfaktur', 'trfinansial.kodekontak', 'mskontak.perusahaan', 'trterimah.total', DB::raw('(trfinansial.jumlah*trfinansial.faktorqty)*-1 as hutang'), 'trterimah.jangkawaktu', DB::raw('DATE_ADD(trterimah.tglfaktur, INTERVAL trterimah.jangkawaktu DAY) as tgljatuhtempo'))
            ->join('trterimah', function ($join) {
                $join->on('trterimah.entiti', '=', 'trfinansial.entiti');
                $join->on('trterimah.nofaktur', '=', 'trfinansial.kodereferensi');
            })->join('mskontak', function ($join) {
                $join->on('mskontak.entiti', '=', 'trfinansial.entiti');
                $join->on('mskontak.kodekontak', '=', 'trfinansial.kodekontak');
            })->where('trfinansial.formid', '=', 'PENERIMAAN')
            ->where('trfinansial.grup', '=', 'HUTANG')
            ->groupBy('trfinansial.entiti', 'trfinansial.kodetransaksi', 'trfinansial.tanggal', 'trterimah.nofaktur', 'trterimah.tglfaktur', 'trfinansial.kodekontak', 'mskontak.perusahaan', 'trterimah.total', 'trterimah.jangkawaktu')
            ->orderBy('tgljatuhtempo')
            ->get();

        return response()->json(
            array(
                'status' => 'ok',
                'data' => $data
            ),
            200
        );
    }
}
