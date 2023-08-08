<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GeneratedTest extends Model
{
    use HasFactory;
    protected $fillable=[
        'egzam',
            'time',
            'test_id',
            'custom_test_id',
            'questions_number',
            'duration'
            
    ];
    public function generatedQuestions():HasMany
    {
        return $this->hasMany(GeneratedQuestion::class);
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function test():BelongsTo
    {
        return $this->belongsTo(Test::class);
    }
}
