<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'indempotency_key',
        'signature','price','subscription_id', 'status', 'payment_id'
    ];


    protected $hidden = [
        'indempotency_key',
        'signature'
       
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
