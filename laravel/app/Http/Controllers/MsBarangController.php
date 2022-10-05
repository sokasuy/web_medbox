<?php

namespace App\Http\Controllers;

use App\Models\MsBarang;
use Illuminate\Http\Request;
use DataTables;

class MsBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = MsBarang::whereNotNull('sku')->orderBy('sku', 'ASC')->take(20)->get();
        return view('msbarang.index', compact('data'));
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
     * @param  \App\Models\MsBarang  $msBarang
     * @return \Illuminate\Http\Response
     */
    public function show(MsBarang $msBarang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MsBarang  $msBarang
     * @return \Illuminate\Http\Response
     */
    public function edit(MsBarang $msBarang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MsBarang  $msBarang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MsBarang $msBarang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MsBarang  $msBarang
     * @return \Illuminate\Http\Response
     */
    public function destroy(MsBarang $msBarang)
    {
        //
    }
}
