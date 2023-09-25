<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAdress extends Model
{
    use HasFactory;
    protected $fillable = [
        'adress',
        'city',
        'postal_code',
        'user_id',
        'nip',
        'newsletter'
    ];
    public function user() :BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
