<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Flashcard extends Model
{
    use HasFactory;

    protected $fillable=[
        'question','answer','path',
    ];

    public function subscriptions():MorphToMany
    {
        return $this->morphedByMany(Subscription::class, 'flashcardable');
    }

    public function undercategories() : BelongsToMany
    {
        return $this->belongsToMany(Undercategory::class);
    }

    public function categories() : BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
  
}
