<?php

namespace App\Models;

use GuzzleHttp\Psr7\Query;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OpenAnswer extends Model
{
    use HasFactory;

    protected $fillable=[
        'answer','grade'
    ];

    public function generatedQuestion():BelongsTo
    {
        return $this->belongsTo(GeneratedQuestion::class);
    }
}
