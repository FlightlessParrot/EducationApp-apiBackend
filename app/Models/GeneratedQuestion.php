<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeneratedQuestion extends Model
{
    use HasFactory;

    public function GeneratedTest():BelongsTo
    {
        return $this->belongsTo(GeneratedTest::class);
    }

    public function Question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
