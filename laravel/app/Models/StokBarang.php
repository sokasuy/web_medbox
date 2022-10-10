<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
