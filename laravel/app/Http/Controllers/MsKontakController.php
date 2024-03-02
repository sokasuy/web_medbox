<?php

namespace App\Http\Controllers;

use App\Models\MsKontak;
use Illuminate\Http\Request;
use App\Models\User;
// use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

use Session;
use Carbon\Carbon;
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
        // $data = MsKontak::getDataListCustomers();
        // dd(DB::getQueryLog());
        // dd($data);

        $kriteria = $request->get('kriteria');
        $isiFilter = $request->get('isiFilter');

        if ($kriteria == "hari_ini") {
            // $data = Sales::getPenjualanByPeriode($kriteria, Carbon::now()->toDateString());
            $isiFilter  = Carbon::now()->toDateString();
        } else if ($kriteria == "3_hari") {
            // $data = Sales::getPenjualanByPeriode($kriteria, Carbon::now()->subDays(3)->toDateString());
            $isiFilter  = Carbon::now()->subDays(3)->toDateString();
        } else if ($kriteria == "7_hari") {
            // $data = Sales::getPenjualanByPeriode($kriteria, Carbon::now()->subDays(7)->toDateString());
            $isiFilter  = Carbon::now()->subDays(7)->toDateString();
        } else if ($kriteria == "14_hari") {
            // $data = Sales::getPenjualanByPeriode($kriteria, Carbon::now()->subDays(14)->toDateString());
            $isiFilter  = Carbon::now()->subDays(14)->toDateString();
        } else if ($kriteria == "bulan_berjalan") {
            // $data = Sales::getPenjualanByPeriode($kriteria, Carbon::now());
            $isiFilter  = Carbon::now();
        } else if (
            $kriteria == "semua"
        ) {
            // $data = Sales::getPenjualanByPeriode($kriteria, $isiFilter);
        } else if ($kriteria == "berdasarkan_tanggal_penjualan") {
            // $data = Sales::getPenjualanByPeriode($kriteria, $isiFilter);
        }
        $data = MsKontak::getDataListCustomersByPeriode($kriteria, $isiFilter);

        return response()->json(
            array(
                'status' => 'ok',
                'data' => $data
            ),
            200
        );
    }

    public function customerIndividualConfirmation(Request $request)
    {
        //
        // DB::enableQueryLog();
        $entiti = $request->get('entiti');
        // dd($entiti);
        // print_r($entiti);
        $kodekontak = $request->get('kodekontak');
        // dd($kodekontak);
        // $data = MsKontak::find($kodekontak);
        $data = MsKontak::where('entiti', $entiti)->where('kodekontak', $kodekontak)->get();
        // dd(DB::getQueryLog());
        $data = $data[0];
        // dd($data);
        return response()->json(
            array(
                'status' => 'ok',
                'msg' => view('auth.custconfirmform', compact('data'))->render()
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

    public function addCustomersToUser(Request $request)
    {
        try {

            // DB::enableQueryLog();
            $user = new User();
            $user->validatorCustomer($request->all())->validate();
            $user->name = $request->get('name');
            $user->email = $request->get('email');
            $user->password = Hash::make($request->get('password'));
            $user->type = 'external';
            $user->kodekontak = $request->get('kodekontak');
            $user->save();

            // $affected = DB::table('users')
            //     ->where('id', 1)
            //     ->update(['votes' => 1]);

            $MsKontak = MsKontak::where('entiti', $request->get('entiti'))->where('kodekontak', $request->get('kodekontak'))->update(['connuser' => 1, 'connectedtousers'  => 1]);
            // $MsKontak = $MsKontak[0];
            // dd($MsKontak);
            // dd(DB::getQueryLog());
            // $MsKontak->connuser = '1';
            // $MsKontak->connectedtousers = '1';
            // $MsKontak->save();

            return response()->json(
                array(
                    'status' => 'ok',
                    'msg' => "<div class='fas fa-bell alert alert-success' style='margin-bottom:10px;'> Customer '" . $user->name . "' inserted to users</div>"
                ),
                200
            );
        } catch (\PDOException $e) {
            return response()->json(
                array(
                    'status' => 'error',
                    'msg' => "<div class='fa fa-bell-o alert alert-info' style='margin-bottom:10px;'> Customer '" . $user->name . "' failed to insert!!.</div>"
                ),
                200
            );
        }
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
