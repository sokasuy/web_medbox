<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use DB;
use DateTime;

class Finansial extends Model
{
    use HasFactory;
    protected $table = 'trfinansial';
    protected $primaryKey = ['entiti', 'kodetransaksi'];
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;
    // const CREATED_AT = 'rid';
    const CREATED_AT = 'adddate';
    const UPDATED_AT = 'editdate';

    public static function getHutangByPeriode($kriteria, $isiFilter = null)
    {
        if ($kriteria == "semua") {
            $data = self::on()->select('trfinansial.entiti', 'trfinansial.kodetransaksi', 'trfinansial.tanggal', 'trterimah.nofaktur', 'trterimah.tglfaktur', 'trfinansial.kodekontak', 'mskontak.perusahaan', 'trterimah.total', DB::raw('(trfinansial.jumlah*trfinansial.faktorqty)*-1 as hutang'), 'trterimah.jangkawaktu', DB::raw('DATE_ADD(trterimah.tglfaktur, INTERVAL trterimah.jangkawaktu DAY) as tgljatuhtempo'))
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
        } elseif ($kriteria == "sudah_jatuh_tempo") {
            $data = self::on()->select('trfinansial.entiti', 'trfinansial.kodetransaksi', 'trfinansial.tanggal', 'trterimah.nofaktur', 'trterimah.tglfaktur', 'trfinansial.kodekontak', 'mskontak.perusahaan', 'trterimah.total', DB::raw('(trfinansial.jumlah*trfinansial.faktorqty)*-1 as hutang'), 'trterimah.jangkawaktu', DB::raw('DATE_ADD(trterimah.tglfaktur, INTERVAL trterimah.jangkawaktu DAY) as tgljatuhtempo'))
                ->join('trterimah', function ($join) {
                    $join->on('trterimah.entiti', '=', 'trfinansial.entiti');
                    $join->on('trterimah.nofaktur', '=', 'trfinansial.kodereferensi');
                })->join('mskontak', function ($join) {
                    $join->on('mskontak.entiti', '=', 'trfinansial.entiti');
                    $join->on('mskontak.kodekontak', '=', 'trfinansial.kodekontak');
                })->where('trfinansial.formid', '=', 'PENERIMAAN')
                ->where('trfinansial.grup', '=', 'HUTANG')
                ->where(DB::raw('DATE_ADD(trterimah.tglfaktur, INTERVAL trterimah.jangkawaktu DAY)'), '<', $isiFilter)
                ->groupBy('trfinansial.entiti', 'trfinansial.kodetransaksi', 'trfinansial.tanggal', 'trterimah.nofaktur', 'trterimah.tglfaktur', 'trfinansial.kodekontak', 'mskontak.perusahaan', 'trterimah.total', 'trterimah.jangkawaktu')
                ->orderBy('tgljatuhtempo')
                ->get();
        } elseif ($kriteria == "30_hari_sebelum_jatuh_tempo" || $kriteria == "15_hari_sebelum_jatuh_tempo" || $kriteria == "7_hari_sebelum_jatuh_tempo") {
            $isiFilter = explode(" s.d ", $isiFilter);

            $data = self::on()->select('trfinansial.entiti', 'trfinansial.kodetransaksi', 'trfinansial.tanggal', 'trterimah.nofaktur', 'trterimah.tglfaktur', 'trfinansial.kodekontak', 'mskontak.perusahaan', 'trterimah.total', DB::raw('(trfinansial.jumlah*trfinansial.faktorqty)*-1 as hutang'), 'trterimah.jangkawaktu', DB::raw('DATE_ADD(trterimah.tglfaktur, INTERVAL trterimah.jangkawaktu DAY) as tgljatuhtempo'))
                ->join('trterimah', function ($join) {
                    $join->on('trterimah.entiti', '=', 'trfinansial.entiti');
                    $join->on('trterimah.nofaktur', '=', 'trfinansial.kodereferensi');
                })->join('mskontak', function ($join) {
                    $join->on('mskontak.entiti', '=', 'trfinansial.entiti');
                    $join->on('mskontak.kodekontak', '=', 'trfinansial.kodekontak');
                })->where('trfinansial.formid', '=', 'PENERIMAAN')
                ->where('trfinansial.grup', '=', 'HUTANG')
                ->where(DB::raw('DATE_ADD(trterimah.tglfaktur, INTERVAL trterimah.jangkawaktu DAY)'), '>=', $isiFilter[0])
                ->where(DB::raw('DATE_ADD(trterimah.tglfaktur, INTERVAL trterimah.jangkawaktu DAY)'), '<=', $isiFilter[1])
                ->groupBy('trfinansial.entiti', 'trfinansial.kodetransaksi', 'trfinansial.tanggal', 'trterimah.nofaktur', 'trterimah.tglfaktur', 'trfinansial.kodekontak', 'mskontak.perusahaan', 'trterimah.total', 'trterimah.jangkawaktu')
                ->orderBy('tgljatuhtempo')
                ->get();
        } elseif ($kriteria == "berdasarkan_tanggal_jatuh_tempo") {
            $isiFilter = explode(" - ", $isiFilter);
            $isiFilter[0] = explode("/", $isiFilter[0]);
            $isiFilter[1] = explode("/", $isiFilter[1]);
            $begin = new DateTime($isiFilter[0][2] . "-" . $isiFilter[0][0] . "-" . $isiFilter[0][1]);
            $end = new DateTime($isiFilter[1][2] . "-" . $isiFilter[1][0] . "-" . $isiFilter[1][1]);

            $data = self::on()->select('trfinansial.entiti', 'trfinansial.kodetransaksi', 'trfinansial.tanggal', 'trterimah.nofaktur', 'trterimah.tglfaktur', 'trfinansial.kodekontak', 'mskontak.perusahaan', 'trterimah.total', DB::raw('(trfinansial.jumlah*trfinansial.faktorqty)*-1 as hutang'), 'trterimah.jangkawaktu', DB::raw('DATE_ADD(trterimah.tglfaktur, INTERVAL trterimah.jangkawaktu DAY) as tgljatuhtempo'))
                ->join('trterimah', function ($join) {
                    $join->on('trterimah.entiti', '=', 'trfinansial.entiti');
                    $join->on('trterimah.nofaktur', '=', 'trfinansial.kodereferensi');
                })->join('mskontak', function ($join) {
                    $join->on('mskontak.entiti', '=', 'trfinansial.entiti');
                    $join->on('mskontak.kodekontak', '=', 'trfinansial.kodekontak');
                })->where('trfinansial.formid', '=', 'PENERIMAAN')
                ->where('trfinansial.grup', '=', 'HUTANG')
                ->where(DB::raw('DATE_ADD(trterimah.tglfaktur, INTERVAL trterimah.jangkawaktu DAY)'), '>=', $begin)
                ->where(DB::raw('DATE_ADD(trterimah.tglfaktur, INTERVAL trterimah.jangkawaktu DAY)'), '<=', $end)
                ->groupBy('trfinansial.entiti', 'trfinansial.kodetransaksi', 'trfinansial.tanggal', 'trterimah.nofaktur', 'trterimah.tglfaktur', 'trfinansial.kodekontak', 'mskontak.perusahaan', 'trterimah.total', 'trterimah.jangkawaktu')
                ->orderBy('tgljatuhtempo')
                ->get();
        }
        return $data;
    }
}
