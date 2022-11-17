<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use DB;
use DateTime;

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

    public static function getExpiryDateByPeriode($kriteria, $isiFilter = null)
    {
        if ($kriteria == "semua") {
            $data = self::on()->select('stokbarang.entiti', 'msbarang.sku', 'msbarang.namabarang', DB::raw('Sum(stokbarang.qty) as jumlah'), 'msbarang.satk', 'msbarang.golongan', 'msbarang.kategori', 'stokbarang.nobatch', 'stokbarang.ed', DB::raw('DATEDIFF(stokbarang.ed,now()) AS harimenujuexpired'), 'msbarang.pabrik', 'msbarang.jenis', 'msbarang.discontinue')
                ->join('msbarang', function ($join) {
                    $join->on('msbarang.entiti', '=', 'stokbarang.entiti');
                    $join->on('msbarang.sku', '=', 'stokbarang.sku');
                })
                ->groupBy('stokbarang.entiti', 'msbarang.sku', 'msbarang.namabarang', 'msbarang.satk', 'msbarang.golongan', 'msbarang.kategori', 'stokbarang.nobatch', 'stokbarang.ed', 'msbarang.pabrik', 'msbarang.jenis', 'msbarang.discontinue')
                ->orderBy('stokbarang.ed')->orderByDesc('jumlah')
                ->get();
        } elseif ($kriteria == "sudah_expired") {
            $data = self::on()->select('stokbarang.entiti', 'msbarang.sku', 'msbarang.namabarang', DB::raw('Sum(stokbarang.qty) as jumlah'), 'msbarang.satk', 'msbarang.golongan', 'msbarang.kategori', 'stokbarang.nobatch', 'stokbarang.ed', DB::raw('DATEDIFF(stokbarang.ed,now()) AS harimenujuexpired'), 'msbarang.pabrik', 'msbarang.jenis', 'msbarang.discontinue')
                ->join('msbarang', function ($join) {
                    $join->on('msbarang.entiti', '=', 'stokbarang.entiti');
                    $join->on('msbarang.sku', '=', 'stokbarang.sku');
                })
                ->where('stokbarang.ed', '<', $isiFilter)
                ->groupBy('stokbarang.entiti', 'msbarang.sku', 'msbarang.namabarang', 'msbarang.satk', 'msbarang.golongan', 'msbarang.kategori', 'stokbarang.nobatch', 'stokbarang.ed', 'msbarang.pabrik', 'msbarang.jenis', 'msbarang.discontinue')
                ->orderBy('stokbarang.ed')->orderByDesc('jumlah')
                ->get();
        } elseif ($kriteria == "30_hari_sebelum_expired" || $kriteria == "15_hari_sebelum_expired" || $kriteria == "7_hari_sebelum_expired") {
            $isiFilter = explode(" s.d ", $isiFilter);
            $data = self::on()->select('stokbarang.entiti', 'msbarang.sku', 'msbarang.namabarang', DB::raw('Sum(stokbarang.qty) as jumlah'), 'msbarang.satk', 'msbarang.golongan', 'msbarang.kategori', 'stokbarang.nobatch', 'stokbarang.ed', DB::raw('DATEDIFF(stokbarang.ed,now()) AS harimenujuexpired'), 'msbarang.pabrik', 'msbarang.jenis', 'msbarang.discontinue')
                ->join('msbarang', function ($join) {
                    $join->on('msbarang.entiti', '=', 'stokbarang.entiti');
                    $join->on('msbarang.sku', '=', 'stokbarang.sku');
                })
                ->where('stokbarang.ed', '>=', $isiFilter[0])
                ->where('stokbarang.ed', '<=', $isiFilter[1])
                ->groupBy('stokbarang.entiti', 'msbarang.sku', 'msbarang.namabarang', 'msbarang.satk', 'msbarang.golongan', 'msbarang.kategori', 'stokbarang.nobatch', 'stokbarang.ed', 'msbarang.pabrik', 'msbarang.jenis', 'msbarang.discontinue')
                ->orderBy('stokbarang.ed')->orderByDesc('jumlah')
                ->get();
        } elseif ($kriteria == "berdasarkan_tanggal_expired") {
            $isiFilter = explode(" - ", $isiFilter);
            $isiFilter[0] = explode("/", $isiFilter[0]);
            $isiFilter[1] = explode("/", $isiFilter[1]);
            $begin = new DateTime($isiFilter[0][2] . "-" . $isiFilter[0][0] . "-" . $isiFilter[0][1]);
            $end = new DateTime($isiFilter[1][2] . "-" . $isiFilter[1][0] . "-" . $isiFilter[1][1]);

            $data = self::on()->select('stokbarang.entiti', 'msbarang.sku', 'msbarang.namabarang', DB::raw('Sum(stokbarang.qty) as jumlah'), 'msbarang.satk', 'msbarang.golongan', 'msbarang.kategori', 'stokbarang.nobatch', 'stokbarang.ed', DB::raw('DATEDIFF(stokbarang.ed,now()) AS harimenujuexpired'), 'msbarang.pabrik', 'msbarang.jenis', 'msbarang.discontinue')
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
        return $data;
    }
}
