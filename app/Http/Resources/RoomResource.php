<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class RoomResource extends JsonResource
{

    public function toArray($request)
    {
        $user = Auth::user()->id;

        if ($user == $this->users[0]->id){
            $user = $this->users[1]->id;
        }else {
            $user = $this->users[0]->id;
        }

        return [
            'id' => $this->id,
            'lastMessageTime' => $this->lastMessageTime,
            'userId'=>$user
        ];
    }
}
