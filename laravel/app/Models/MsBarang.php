<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsBarang extends Model
{
    use HasFactory;
    protected $table = 'msbarang';
    protected $primaryKey = ['entiti', 'sku'];
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;
    const CREATED_AT = 'adddate';
    const UPDATED_AT = 'editdate';

    public static function getDataMasterBarang()
    {
        $data = self::on()->select('entiti', 'sku', 'barcode', 'namabarang', 'golongan', 'jenis', 'satk', 'konv1', 'satt', 'konv2', 'satb')
            ->whereNotNull('sku')
            ->orderBy('sku', 'ASC')
            ->get();
        return $data;
    }
}
