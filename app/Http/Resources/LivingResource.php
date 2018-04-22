<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LivingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'last_email_sent' => $this->last_email_sent,
            'send_email_after' => $this->send_email_after,
            'last_email_seen' => $this->last_email_seen,
            'token' => $this->token,
            'user' => $this->user,
        ];
    }
}
