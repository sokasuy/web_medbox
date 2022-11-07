<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use DateTime;

class Sales extends Model
{
    use HasFactory;
    protected $table = 'trjualh';
    protected $primaryKey = ['entiti', 'noinvoice'];
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;
    const CREATED_AT = 'adddate';
    const UPDATED_AT = 'editdate';

    public static function getPenjualanByPeriode($kriteria, $isiFilter)
    {
        if ($kriteria == "hari_ini") {
            $data = self::on()->select('trjualh.entiti', 'trjualh.noinvoice', 'trjualh.tanggal', 'trjualh.pembayaran', 'trjuald.sku', 'trjuald.namabarang', 'trjuald.qty', 'trjuald.satuan', 'trjuald.harga', 'trjuald.jumlah', 'trjuald.statusbarang', 'trjualh.adddate', 'trjualh.editdate')
                ->join('trjuald', function ($join) {
                    $join->on('trjuald.entiti', '=', 'trjualh.entiti');
                    $join->on('trjuald.noinvoice', '=', 'trjualh.noinvoice');
                })->where('trjualh.tanggal', '=', $isiFilter)
                ->where('trjuald.faktorqty', '=', -1)
                ->orderBy('trjualh.adddate')
                ->get();
        } else if ($kriteria == "3_hari" || $kriteria == "7_hari" || $kriteria == "14_hari") {
            $data = self::on()->select('trjualh.entiti', 'trjualh.noinvoice', 'trjualh.tanggal', 'trjualh.pembayaran', 'trjuald.sku', 'trjuald.namabarang', 'trjuald.qty', 'trjuald.satuan', 'trjuald.harga', 'trjuald.jumlah', 'trjuald.statusbarang', 'trjualh.adddate', 'trjualh.editdate')
                ->join('trjuald', function ($join) {
                    $join->on('trjuald.entiti', '=', 'trjualh.entiti');
                    $join->on('trjuald.noinvoice', '=', 'trjualh.noinvoice');
                })->where('trjualh.tanggal', '>=', $isiFilter)
                ->where('trjuald.faktorqty', '=', -1)
                ->orderBy('trjualh.adddate')
                ->get();
        } else if ($kriteria == "bulan_berjalan") {
            $data = self::on()->select('trjualh.entiti', 'trjualh.noinvoice', 'trjualh.tanggal', 'trjualh.pembayaran', 'trjuald.sku', 'trjuald.namabarang', 'trjuald.qty', 'trjuald.satuan', 'trjuald.harga', 'trjuald.jumlah', 'trjuald.statusbarang', 'trjualh.adddate', 'trjualh.editdate')
                ->join('trjuald', function ($join) {
                    $join->on('trjuald.entiti', '=', 'trjualh.entiti');
                    $join->on('trjuald.noinvoice', '=', 'trjualh.noinvoice');
                })->whereYear('trjualh.tanggal', '=', $isiFilter->year)
                ->whereMonth('trjualh.tanggal', '=', $isiFilter->month)
                ->where('trjuald.faktorqty', '=', -1)
                ->orderBy('trjualh.adddate')
                ->get();
        } else if ($kriteria == "semua") {
            $data = self::on()->select('trjualh.entiti', 'trjualh.noinvoice', 'trjualh.tanggal', 'trjualh.pembayaran', 'trjuald.sku', 'trjuald.namabarang', 'trjuald.qty', 'trjuald.satuan', 'trjuald.harga', 'trjuald.jumlah', 'trjuald.statusbarang', 'trjualh.adddate', 'trjualh.editdate')
                ->join('trjuald', function ($join) {
                    $join->on('trjuald.entiti', '=', 'trjualh.entiti');
                    $join->on('trjuald.noinvoice', '=', 'trjualh.noinvoice');
                })
                ->where('trjuald.faktorqty', '=', -1)
                ->orderBy('trjualh.adddate')
                ->get();
        } else if ($kriteria == "berdasarkan_tanggal_penjualan") {
            $isiFilter = explode(" - ", $isiFilter);
            $isiFilter[0] = explode("/", $isiFilter[0]);
            $isiFilter[1] = explode("/", $isiFilter[1]);
            $begin = new DateTime($isiFilter[0][2] . "-" . $isiFilter[0][0] . "-" . $isiFilter[0][1]);
            $end = new DateTime($isiFilter[1][2] . "-" . $isiFilter[1][0] . "-" . $isiFilter[1][1]);

            $data = self::on()->select('trjualh.entiti', 'trjualh.noinvoice', 'trjualh.tanggal', 'trjualh.pembayaran', 'trjuald.sku', 'trjuald.namabarang', 'trjuald.qty', 'trjuald.satuan', 'trjuald.harga', 'trjuald.jumlah', 'trjuald.statusbarang', 'trjualh.adddate', 'trjualh.editdate')
                ->join('trjuald', function ($join) {
                    $join->on('trjuald.entiti', '=', 'trjualh.entiti');
                    $join->on('trjuald.noinvoice', '=', 'trjualh.noinvoice');
                })
                ->where('trjualh.tanggal', '>=', $begin)
                ->where('trjualh.tanggal', '<=', $end)
                ->where('trjuald.faktorqty', '=', -1)
                ->orderBy('trjualh.adddate')
                ->get();
        }
        return $data;
    }
}
