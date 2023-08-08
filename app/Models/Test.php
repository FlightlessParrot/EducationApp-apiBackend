<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Test extends Model
{
    use HasFactory;

    protected $fillable=[
        'name', 'custom'
    ];
    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class);

    }

    public function users():BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function generatedTests():HasMany
    {
        return $this->hasMany(GeneratedTest::class);
    }

    public function teams():BelongsToMany
    {
        return $this->belongsToMany(Test::class);
    }

}
