<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Test extends Model
{
    use HasFactory;

    protected $fillable=[
        'name', 'role', 'fillable', 'maximum_time', 'path', 'gandalf', 'user_id', 'subscription_id', 'team_id'
    ];
    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class);

    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function generatedTests():HasMany
    {
        return $this->hasMany(GeneratedTest::class);
    }

    public function team(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }

    public function notyfications():MorphMany
    {
        return $this->morphMany(Notyfication::class, "notyficationable");
    }

    public function subscriptions() : BelongsToMany
    {
        return $this->belongsToMany(Subscription::class);
    }

}
