<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Test extends Model
{
    use HasFactory;

    protected $fillable=[
        'name', 'role', 'fillable', 'maximum_time', 'path', 'gandalf'
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
        return $this->belongsToMany(Team::class);
    }

    public function notyfications():MorphMany
    {
        return $this->morphMany(Notyfication::class, "notyficationable");
    }

}
