<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    protected $fillable=[   
        'discount','code'

    ];
    use HasFactory;
}
