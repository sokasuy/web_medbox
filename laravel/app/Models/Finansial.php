<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finansial extends Model
{
    use HasFactory;
    protected $table = 'trfinansial';
    protected $primaryKey = ['entiti', 'kodetransaksi'];
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;
    const CREATED_AT = 'rid';
}
