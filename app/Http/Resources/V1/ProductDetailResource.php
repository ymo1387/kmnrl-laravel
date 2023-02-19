<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'price' => $this->price,
            'instock' => $this->instock->instock,
            'specifications' => $this->specifications->pluck('text'),
            'description' => $this->description,
            'images' => [
                'main' => $this->images->where('type','main')->first(),
                'sec' => $this->images->where('type','sec')->all(),
                'other' => $this->images->where('type','other')->all(),
            ],
            'variants' => $this->family_id
                ? new VariantCollection($this->variants()->load('mainImage'))
                : null,
        ];
    }

    public static $wrap = null;
}
