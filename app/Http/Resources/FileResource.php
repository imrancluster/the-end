<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $file = [
            'id'      => $this->id,
            'url'     => asset($this->uri),
            'deleted'     => ($this->deleted_at != null) ? true : false,
        ];

        return $file;
    }
}
