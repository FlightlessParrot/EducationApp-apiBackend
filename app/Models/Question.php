<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Question extends Model
{
    use HasFactory;
    protected $fillable=[
        'question', 'custom','category_id', 'undercategory_id','type','path','explanation'
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
    public function shortAnswer() :HasOne{
        return $this->hasOne(ShortAnswer::class);
    }
    public function generatedQuestions() :HasMany
    {
        return $this->hasMany(GeneratedQuestion::class);
    }

    public function categories():BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function undercategories():BelongsToMany
    {
        return $this->belongsToMany(Undercategory::class);
    }

}
