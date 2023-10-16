<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Flashcard extends Model
{
    use HasFactory;

    protected $fillable=[
        'question','answer','path','undercategory_id', 'category_id'
    ];

    public function subscriptions():MorphToMany
    {
        return $this->morphedByMany(Subscription::class, 'flashcardable');
    }

    public function undercategory()
    {
        return $this->belongsTo(Undercategory::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
  
}
