<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Square extends Model
{
    use HasFactory;

    protected $fillable=[
        'brother', 'question_id', 'order', 'name'
    ];
    protected $hidden=[
    //    'order', 'brother'
    ];
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

}
