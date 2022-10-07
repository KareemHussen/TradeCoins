<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['text', 'user_id', 'room_id'];


    public function user(){
        return $this->belongsTo(User::class , "user_id");
    }

    public function room() : BelongsTo{
        return $this->belongsTo(Room::class , "room_id");
    }

}
