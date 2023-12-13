<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable=[
        'name'
    ];

    public function questions():BelongsToMany
    {
        return $this->belongsToMany(Question::class);
    }

    function flashcards() : BelongsToMany
    {
        return $this->belongsToMany(Flashcard::class);
    }

    function undercategories() : HasMany
    {
        return $this->hasMany(Undercategory::class);
    }
}
