<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;
class CartResource extends JsonResource
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
             'price'=>$this->price,
            'quantity'=>$this->quantity,
            'user_id'=>$this->user_id,
            'product'=>$this->products ,
            'size_id'=>$this->size_id,
            'size_name'=>DB::table('sizes')->where('id','=',$this->size_id)->get('size'),


        ];
    }
}
