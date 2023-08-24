<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;
    protected $fillable=[
        'question', 'custom','category_id', 'undercategory_id','type','path'
    ];
    public function tests() :BelongsToMany
    {
        return $this->belongsToMany(Test::class);
    }
    public function answers() :HasMany
    {
        return $this->hasMany(Answer::class);
    }
    public function squares() :HasMany
    {
        return $this->hasMany(Square::class);
    }
    
    public function generatedQuestions() :HasMany
    {
        return $this->hasMany(GeneratedQuestion::class);
    }

    public function category():BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function undercategory():BelongsTo
    {
        return $this->belongsTo(Undercategory::class);
    }

}
