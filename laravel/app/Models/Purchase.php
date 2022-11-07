<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use DB;

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
}
