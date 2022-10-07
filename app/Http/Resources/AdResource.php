<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AdResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'min' => $this->min,
            'buy_sell' => $this->buy_sell,
            'theMethod' => $this->theMethod,
            'note' => $this->note,
            'price' => $this->price,
            'user_id' => $this->user_id,
            'created_at' => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('Y-m-d g:i A'),
        ];
    }
}
