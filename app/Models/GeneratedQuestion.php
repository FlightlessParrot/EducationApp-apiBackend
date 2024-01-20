<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class GeneratedQuestion extends Model
{
    use HasFactory;
    protected $fillable=['question_id', 'answer', 'relevant', 'generated_test_id','question_id'];
    public function generatedTest():BelongsTo
    {
        return $this->belongsTo(GeneratedTest::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function openAnswer():   HasOne
    {
        return $this->hasOne(OpenAnswer::class);
    }
}
