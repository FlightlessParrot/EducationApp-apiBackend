<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GeneratedTest extends Model
{
    use HasFactory;
    protected $fillable=[
        'egzam',
            'time',
            'test_id'
    ];
    public function generatedQuestions():HasMany
    {
        return $this->hasMany(GeneratedQuestion::class);
    }
}
