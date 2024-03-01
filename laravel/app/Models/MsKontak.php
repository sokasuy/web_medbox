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

    public static function getDataListCustomers()
    {
        // DB::enableQueryLog();
        $data = self::on()->select('entiti', 'kodekontak', 'kontak', 'hp', 'adddate as created_at', 'editdate as updated_at')
            ->where('jeniskontak', '=', 'pelanggan')->whereNotNull('hp')->where(function ($query) {
                return $query
                    ->whereNull('connectedtousers')
                    ->orWhere('connectedtousers', '=', '0');
            })
            ->OrderByDesc('adddate')
            ->get();
        // dd(DB::getQueryLog());
        return $data;
    }
}
