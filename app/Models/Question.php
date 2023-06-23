<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    public function Test() :BelongsTo
    {
        return $this->belongsTo(Test::class);
    }
    public function Answer() :HasMany
    {
        return $this->hasMany(Answer::class);
    }
}
