<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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

    public function tests():BelongsToMany
    {
        return $this->belongsToMany(Test::class);
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
}
