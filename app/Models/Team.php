<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable=['name'];

    public function users():BelongsToMany
    {
        
        return $this->belongsToMany(User::class)->withPivot('created_at','updated_at','is_teacher');
    }

    public function tests() :BelongsToMany
    {
        return $this->belongsToMany(Test::class);
    }

    public function flashcards(): MorphMany
    {
        return $this->morphMany(Flashcard::class, 'flashcardable');
    }
}
