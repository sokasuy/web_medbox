<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Helper extends Model
{
    use HasFactory;

    public static $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
}
