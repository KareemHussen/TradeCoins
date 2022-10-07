<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;

    protected $fillable = ['min' , 'max' , 'buy_sell' , 'tags' , 'price' , 'theMethod' , 'note' , 'user_id'];


    protected $casts = [
      'tags'=>'array',
    ];

    function feedbacks(){
        return $this->hasMany(FeedBack::class , 'ad_id');
    }
}
