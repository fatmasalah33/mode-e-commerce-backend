<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCollection;

use App\Models\Offer;

class OfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return[
                'id'=>$this->id,
                'end_at'=>$this->end_at,
                'percent'=>$this->percent,
                 //'product_id'=>$this->product_id,
                'product'=>$this->products


        ];
    }
}
