<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use DateTime;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
       
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function sendPasswordResetNotification($token): void
    {
     
        $this->notify(new ResetPasswordNotification($token));
    }

    public function userAdress(): HasOne
    {
        return $this->hasOne(UserAdress::class);
    }

    public function payments() : HasMany
    {
        return $this->hasMany(Payment::class);
    }
    public function generatedTests():HasMany
    {
        return $this->hasMany(GeneratedTest::class);
    }
 
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class)->withPivot('created_at','updated_at','is_teacher');
    }

    public function notyfications():HasMany
    {
        return $this->hasMany(Notyfication::class);
    }
    public function flashcards():MorphMany
    {
        return $this->morphMany(Flashcard::class, 'flashcardable');
    }
    public function subscriptions()
    {
        $date=new DateTime();
        return $this->morphToMany(Subscription::class, 'subscriptionables')->withPivot('expiration_date')->wherePivot('expiration_date','>=',$date);
    }

    public function tests() :Collection
    {
        $tests= new Collection();
        $user=Auth::user();
      foreach($this->subscriptions as $subscription)
      {
        $tests=$tests->merge($subscription->tests()->where(function (Builder $querry) use ($user){
            return $querry->whereNull('user_id')->orWhere('user_id',$user->id);
        })->where('role','!=','egzam')->get());
      }
    
      return $tests->unique()->values();
    }
}
