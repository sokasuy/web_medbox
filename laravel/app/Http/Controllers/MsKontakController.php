<?php

namespace App\Http\Controllers;

use App\Models\MsKontak;
use Illuminate\Http\Request;

use DB;

class MsKontakController extends Controller
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

    public function customers()
    {
        return view('auth.customers');
    }

    public function getCustomersList(Request $request)
    {
        // DB::enableQueryLog();
        $data = MsKontak::getDataListCustomers();
        // dd(DB::getQueryLog());
        // dd($data);
        return response()->json(
            array(
                'status' => 'ok',
                'data' => $data
            ),
            200
        );
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
     * @param  \App\Models\MsKontak  $msKontak
     * @return \Illuminate\Http\Response
     */
    public function show(MsKontak $msKontak)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MsKontak  $msKontak
     * @return \Illuminate\Http\Response
     */
    public function edit(MsKontak $msKontak)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MsKontak  $msKontak
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MsKontak $msKontak)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MsKontak  $msKontak
     * @return \Illuminate\Http\Response
     */
    public function destroy(MsKontak $msKontak)
    {
        //
    }
}
