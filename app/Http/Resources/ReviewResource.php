<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'id'=>$this->id,
            'feedback'=>$this->feedback,
            'rating'=>$this->rating,
            'user_name'=>$this->user->name,
            'product_name'=>$this->Product->name,
            'user_id'=>$this->user_id,
            'product_id'=>$this->product_id,
            'created_at'=>$this->created_at

        ];
    }
}
