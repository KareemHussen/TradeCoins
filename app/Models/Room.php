<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['lastMessageTime' , 'firstUser' , 'secondUser'];

    public $timestamps = false;

    public function messages() : HasMany{
        return $this->hasMany(Message::class , "room_id");
    }

    public function users(){
        return $this->belongsToMany(User::class , "room_user");
    }


}
