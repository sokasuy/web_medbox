<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use DB;

class StokBarang extends Model
{
    use HasFactory;
    protected $table = 'stokbarang';
    protected $primaryKey = 'rid';
    public $incrementing = true;
    // protected $keyType = 'integer';
    public $timestamps = false;
    // const CREATED_AT = 'adddate';
    // const UPDATED_AT = 'editdate';

    public static function getHPPPenjualan($kriteria, $isiFilter)
    {
        if ($kriteria == "bulanan") {
            $data = self::on()->join('trjualh', function ($join) {
                $join->on('trjualh.noinvoice', '=', 'stokbarang.kodereferensi');
                $join->on('trjualh.entiti', '=', 'stokbarang.entiti');
            })
                ->select(DB::raw("SUM(stokbarang.qty*stokbarang.hpp) as hppsales"), DB::raw("MONTHNAME(trjualh.tanggal) as bulan"))
                ->whereExists(function ($query) use ($isiFilter) {
                    $query->select(DB::raw(1))
                        ->from('trjuald')
                        ->whereColumn('trjualh.entiti', 'trjuald.entiti')
                        ->whereColumn('trjualh.noinvoice', 'trjuald.noinvoice')
                        ->where('trjuald.faktorqty', '=', -1)
                        ->whereYear('trjualh.tanggal', '=', $isiFilter);
                })
                ->whereYear('trjualh.tanggal', '=', $isiFilter)
                ->groupBy(DB::raw("MONTHNAME(trjualh.tanggal)"))
                ->pluck('hppsales', 'bulan');
        }
        return $data;
    }

    public static function getReturHPPPenjualan($kriteria, $isiFilter)
    {
        if ($kriteria == "bulanan") {
            $data = self::on()->join('trjualh', function ($join) {
                $join->on('trjualh.noinvoice', '=', 'stokbarang.kodereferensi');
                $join->on('trjualh.entiti', '=', 'stokbarang.entiti');
            })
                ->select(DB::raw("SUM(stokbarang.qty*stokbarang.hpp) as hppretursales"), DB::raw("MONTHNAME(trjualh.tanggal) as bulan"))
                ->whereExists(function ($query) use ($isiFilter) {
                    $query->select(DB::raw(1))
                        ->from('trjuald')
                        ->whereColumn('trjualh.entiti', 'trjuald.entiti')
                        ->whereColumn('trjualh.noinvoice', 'trjuald.noinvoice')
                        ->where('trjuald.faktorqty', '=', 1)
                        ->whereYear('trjualh.tanggal', '=', $isiFilter);
                })
                ->whereYear('trjualh.tanggal', '=', $isiFilter)
                ->groupBy(DB::raw("MONTHNAME(trjualh.tanggal)"))
                ->pluck('hppretursales', 'bulan');
        }
        return $data;
    }
}
