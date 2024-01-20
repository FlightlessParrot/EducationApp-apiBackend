<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable=['name', 'price','license_duration','active','discount_price', 'lowest_price', 'description'];

    public function users():MorphToMany
    {
        return $this->morphedByMany(User::class,'subscriptionables');
    }
    public function teams(): MorphToMany
    {
        return $this->morphedByMany(Team::class,'subscriptionables');
    }

    public function tests():BelongsToMany
    {
       return $this->belongsToMany(Test::class);
    }

    public function flashcards():MorphToMany
    {
        return $this->morphToMany(Flashcard::class, 'flashcardable');
    }

    public function discount_codes() : BelongsToMany
    {
        return $this->belongsToMany(DiscountCode::class);
    }
}
