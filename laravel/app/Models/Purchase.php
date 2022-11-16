<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use DB;
// use DateTime;
// use Carbon\Carbon;

class Purchase extends Model
{
    use HasFactory;
    protected $table = 'trterimah';
    protected $primaryKey = ['entiti', 'noterima'];
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;
    const CREATED_AT = 'adddate';
    const UPDATED_AT = 'editdate';

    public static function getTahunPembelian()
    {
        $dataTahunPembelian = self::on()->select(DB::raw("YEAR(tanggal) as tahun"))->groupBy(DB::raw("YEAR(tanggal)"))->orderBy(DB::raw("YEAR(tanggal)"))->get();
        return $dataTahunPembelian;
    }

    public static function getPembelianByPeriodeChart($kriteria, $isiFilter = null, $supplier = null)
    {
        if ($kriteria == "bulanan") {
            if (is_null($supplier)) {
                $data = self::on()->select(DB::raw("SUM(subtotal) as totalbeli"), DB::raw("MONTHNAME(tanggal) as bulan"))
                    ->whereExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('trterimad')
                            ->whereColumn('trterimah.entiti', 'trterimad.entiti')
                            ->whereColumn('trterimah.noterima', 'trterimad.noterima')
                            ->where('trterimad.faktorqty', '=', 1);
                    })
                    ->whereYear('tanggal', '=', $isiFilter)
                    ->groupBy(DB::raw("MONTHNAME(tanggal)"))
                    ->pluck('totalbeli', 'bulan');
            } else {
                $data = self::on()->select(DB::raw("SUM(subtotal) as totalbeli"), DB::raw("MONTHNAME(tanggal) as bulan"))
                    ->whereExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('trterimad')
                            ->whereColumn('trterimah.entiti', 'trterimad.entiti')
                            ->whereColumn('trterimah.noterima', 'trterimad.noterima')
                            ->where('trterimad.faktorqty', '=', 1);
                    })
                    ->whereYear('tanggal', '=', $isiFilter)
                    ->where('kodekontak', '=', $supplier)
                    ->groupBy(DB::raw("MONTHNAME(tanggal)"))
                    ->pluck('totalbeli', 'bulan');
            }
        }
        return $data;
    }

    public static function getReturPembelianByPeriodeChart($kriteria, $isiFilter = null, $supplier = null)
    {
        if ($kriteria == "bulanan") {
            if (is_null($supplier)) {
                $data = self::on()->select(DB::raw("SUM(subtotal) as totalretur"), DB::raw("MONTHNAME(tanggal) as bulan"))
                    ->whereExists(function ($query) use ($isiFilter) {
                        $query->select(DB::raw(1))
                            ->from('trterimad')
                            ->whereColumn('trterimah.entiti', 'trterimad.entiti')
                            ->whereColumn('trterimah.noterima', 'trterimad.noterima')
                            ->where('trterimad.faktorqty', '=', -1)
                            ->whereYear('trterimah.tanggal', '=', $isiFilter);
                    })
                    ->whereYear('tanggal', '=', $isiFilter)
                    ->groupBy(DB::raw("MONTHNAME(tanggal)"))
                    ->pluck('totalretur', 'bulan');
            } else {
                $data = self::on()->select(DB::raw("SUM(subtotal) as totalretur"), DB::raw("MONTHNAME(tanggal) as bulan"))
                    ->whereExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('trterimad')
                            ->whereColumn('trterimah.entiti', 'trterimad.entiti')
                            ->whereColumn('trterimah.noterima', 'trterimad.noterima')
                            ->where('trterimad.faktorqty', '=', -1);
                    })
                    ->whereYear('tanggal', '=', $isiFilter)
                    ->where('kodekontak', '=', $supplier)
                    ->groupBy(DB::raw("MONTHNAME(tanggal)"))
                    ->pluck('totalretur', 'bulan');
            }
        }
        return $data;
    }
}
