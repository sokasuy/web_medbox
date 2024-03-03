<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use DateTime;

class MsKontak extends Model
{
    use HasFactory;
    protected $table = 'mskontak';
    protected $primaryKey = ['entiti', 'kodekontak'];
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;
    const CREATED_AT = 'adddate';
    const UPDATED_AT = 'editdate';

    public static function getSupplier()
    {
        $dataSupplier = self::on()->select('kodekontak', 'perusahaan')->where('jeniskontak', 'SUPPLIER')->orderBy('perusahaan')->get();
        return $dataSupplier;
    }

    public static function getDataListCustomersByPeriode($kriteria, $isiFilter)
    {
        // DB::enableQueryLog();
        if ($kriteria == "hari_ini") {
            $data = self::on()->select('entiti', 'kodekontak', 'kontak', 'hp', 'adddate as created_at', 'editdate as updated_at')
                ->where('jeniskontak', 'pelanggan')->whereNotNull('hp')->where(function ($query) {
                    return $query
                        ->whereNull('connectedtousers')
                        ->orWhere('connectedtousers', '0');
                })->where('adddate', '=', $isiFilter)
                ->OrderByDesc('adddate')
                ->get();
        } else if ($kriteria == "3_hari" || $kriteria == "7_hari" || $kriteria == "14_hari") {
            $data = self::on()->select('entiti', 'kodekontak', 'kontak', 'hp', 'adddate as created_at', 'editdate as updated_at')
                ->where('jeniskontak', 'pelanggan')->whereNotNull('hp')->where(function ($query) {
                    return $query
                        ->whereNull('connectedtousers')
                        ->orWhere('connectedtousers', '0');
                })->where('adddate', '>=', $isiFilter)
                ->OrderByDesc('adddate')
                ->get();
        } else if ($kriteria == "bulan_berjalan") {
            $data = self::on()->select('entiti', 'kodekontak', 'kontak', 'hp', 'adddate as created_at', 'editdate as updated_at')
                ->where('jeniskontak', 'pelanggan')->whereNotNull('hp')->where(function ($query) {
                    return $query
                        ->whereNull('connectedtousers')
                        ->orWhere('connectedtousers', '0');
                })->whereYear('adddate', '>=', $isiFilter->year)->whereMonth('adddate', '=', $isiFilter->month)
                ->OrderByDesc('adddate')
                ->get();
        } else if ($kriteria == "semua") {
            $data = self::on()->select('entiti', 'kodekontak', 'kontak', 'hp', 'adddate as created_at', 'editdate as updated_at')
                ->where('jeniskontak', 'pelanggan')->whereNotNull('hp')->where(function ($query) {
                    return $query
                        ->whereNull('connectedtousers')
                        ->orWhere('connectedtousers', '0');
                })
                ->OrderByDesc('adddate')
                ->get();
        } else if ($kriteria == "berdasarkan_tanggal_penjualan") {
            $isiFilter = explode(" - ", $isiFilter);
            $isiFilter[0] = explode("/", $isiFilter[0]);
            $isiFilter[1] = explode("/", $isiFilter[1]);
            $begin = new DateTime($isiFilter[0][2] . "-" . $isiFilter[0][0] . "-" . $isiFilter[0][1]);
            $end = new DateTime($isiFilter[1][2] . "-" . $isiFilter[1][0] . "-" . $isiFilter[1][1]);

            $data = self::on()->select('entiti', 'kodekontak', 'kontak', 'hp', 'adddate as created_at', 'editdate as updated_at')
                ->where('jeniskontak', 'pelanggan')->whereNotNull('hp')->where(function ($query) {
                    return $query
                        ->whereNull('connectedtousers')
                        ->orWhere('connectedtousers', '0');
                })->where('adddate', '>=', $begin)
                ->where('adddate', '<=', $end)
                ->OrderByDesc('adddate')
                ->get();
        }
        return $data;
    }

    public static function getCustomerForBulkInsert($kriteria, $isiFilter)
    {
        // DB::enableQueryLog();
        if ($kriteria == "hari_ini") {
            $data = self::on()->select('entiti', 'kodekontak', 'kontak as name', 'hp as email', 'hp as password')
                ->where('jeniskontak', 'pelanggan')->whereNotNull('hp')->where(function ($query) {
                    return $query
                        ->whereNull('connectedtousers')
                        ->orWhere('connectedtousers', '0');
                })->where('adddate', '=', $isiFilter)->whereRaw('length(hp)>=8')
                ->OrderByDesc('adddate')->take(10)
                ->get();
        } else if ($kriteria == "3_hari" || $kriteria == "7_hari" || $kriteria == "14_hari") {
            $data = self::on()->select('entiti', 'kodekontak', 'kontak as name', 'hp as email', 'hp as password')
                ->where('jeniskontak', 'pelanggan')->whereNotNull('hp')->where(function ($query) {
                    return $query
                        ->whereNull('connectedtousers')
                        ->orWhere('connectedtousers', '0');
                })->where('adddate', '>=', $isiFilter)->whereRaw('length(hp)>=8')
                ->OrderByDesc('adddate')->take(10)
                ->get();
        } else if ($kriteria == "bulan_berjalan") {
            $data = self::on()->select('entiti', 'kodekontak', 'kontak as name', 'hp as email', 'hp as password')
                ->where('jeniskontak', 'pelanggan')->whereNotNull('hp')->where(function ($query) {
                    return $query
                        ->whereNull('connectedtousers')
                        ->orWhere('connectedtousers', '0');
                })->whereYear('adddate', '>=', $isiFilter->year)->whereMonth('adddate', '=', $isiFilter->month)->whereRaw('length(hp)>=8')
                ->OrderByDesc('adddate')->take(10)
                ->get();
        } else if ($kriteria == "semua") {
            $data = self::on()->select('entiti', 'kodekontak', 'kontak as name', 'hp as email', 'hp as password')
                ->where('jeniskontak', 'pelanggan')->whereNotNull('hp')->where(function ($query) {
                    return $query
                        ->whereNull('connectedtousers')
                        ->orWhere('connectedtousers', '0');
                })
                ->whereRaw('length(hp)>=8')
                ->OrderByDesc('adddate')->take(10)
                ->get();
        } else if ($kriteria == "berdasarkan_tanggal_penjualan") {
            $isiFilter = explode(" - ", $isiFilter);
            $isiFilter[0] = explode("/", $isiFilter[0]);
            $isiFilter[1] = explode("/", $isiFilter[1]);
            $begin = new DateTime($isiFilter[0][2] . "-" . $isiFilter[0][0] . "-" . $isiFilter[0][1]);
            $end = new DateTime($isiFilter[1][2] . "-" . $isiFilter[1][0] . "-" . $isiFilter[1][1]);

            $data = self::on()->select('entiti', 'kodekontak', 'kontak as name', 'hp as email', 'hp as password')
                ->where('jeniskontak', 'pelanggan')->whereNotNull('hp')->where(function ($query) {
                    return $query
                        ->whereNull('connectedtousers')
                        ->orWhere('connectedtousers', '0');
                })->where('adddate', '>=', $begin)
                ->where('adddate', '<=', $end)->whereRaw('length(hp)>=8')
                ->OrderByDesc('adddate')->take(10)
                ->get();
        }
        return $data;
    }
}
