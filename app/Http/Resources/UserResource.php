<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserResource extends JsonResource
{


    public function toArray($request)
    {

        $user = User::find($this->id);

        $token = Auth::login($user);


        return [
            'id' => $this->id,
            'name' => $this->name,
            'email'=> $this->email,
            'phone' => $this->phone,
            'money'=> $this-> money,
            'email_verified_at'=> $this->email_verified_at,
            'number_verified_at'=> $this->number_verified_at,
            'goodVote'=> $this->goodVote,
            'badVote'=> $this->badVote,
            'token' => $token
        ];
    }
}
