<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable=['name'];
    protected function  setDate()
    {
        $this->date=new DateTime();
    }
    public function users():BelongsToMany
    {
        
        return $this->belongsToMany(User::class)->withPivot('created_at','updated_at','is_teacher');
    }

    public function subscriptions()
    {
        $this->setDate();
        return $this->morphToMany(Subscription::class, 'subscriptionables')->withPivot('expiration_date')->wherePivot('expiration_date','>=',$this->date);
    }
    public function tests() : BelongsToMany
    {
        $this->setDate();
      return $this->belongsToMany(Test::class)->withPivot('expiration_date')->wherePivot('expiration_date','>=',$this->date);
    }

    public function flashcards(): MorphMany
    {
        return $this->morphMany(Flashcard::class, 'flashcardable');
    }
    public function egzams()
    {
        return $this->hasMany(Test::class);
    }

}
