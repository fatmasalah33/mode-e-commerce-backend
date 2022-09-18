<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;
class buyeraddressesResource extends JsonResource
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
            'address_state'=>DB::table('states')->where('id','=',$this->address_state)->get('name'),
            'address_city'=>DB::table('cities')->where('id','=',$this->address_city)->get('name'),
            'address_street'=>$this->address_street,
            'user_id'=>$this->user_id,
            'name'=>$this->name,
            'phone'=>$this->phone,
            'created_at'=>$this->created_at,
            'default'=>$this->default,


        ];
    }
}
