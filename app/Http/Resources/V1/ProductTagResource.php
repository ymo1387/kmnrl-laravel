<?php

namespace App\Http\Resources\V1;

use App\Http\Resources\V1\TagResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductTagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'name' => $this->tag->name,
            'info' => $this->tag->info
        ];
    }
}
