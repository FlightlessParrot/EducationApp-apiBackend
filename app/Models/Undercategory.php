<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Undercategory extends Model
{
    use HasFactory;
    protected $fillable=[
        'name'
    ];

    function questions() : BelongsToMany
    {
        return $this->belongsToMany(Question::class);
    }
    function flashcards() :BelongsToMany
    {
        return $this->belongsToMany(Flashcard::class);
    }
    function categories():BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
