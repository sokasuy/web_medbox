<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        $dataSupplier = self::on()->select('kodekontak', 'perusahaan')->where('jeniskontak', '=', 'SUPPLIER')->orderBy('perusahaan')->get();
        return $dataSupplier;
    }
}
