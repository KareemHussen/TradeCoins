<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedBack extends Model
{
    use HasFactory;

    protected $fillable = ['review'];


    function feedbacks(){
        return $this->belongsTo(User::class , 'user_id');
    }
}
