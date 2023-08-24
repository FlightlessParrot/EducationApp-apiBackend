<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Flashcard extends Model
{
    use HasFactory;

    protected $fillable=[
        'question','answer','path','undercategory_id', 'category_id'
    ];

    public function flashcardable():MorphTo
    {
        return $this->morphTo();
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
