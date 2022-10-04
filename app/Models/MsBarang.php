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
}
