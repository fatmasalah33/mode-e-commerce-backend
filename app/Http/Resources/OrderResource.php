<?php

namespace App\Http\Resources;

use App\Models\buyerAddresses;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Order;
use DB;
class OrderResource extends JsonResource
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
            'user_id'=>$this->user->id,
            'price'=>$this->price,
            'comment'=>$this->comment,
            'copoun'=>$this->copoun,
            'payment_id'=>$this->payment,
            'status'=>$this->status,
            'created_at'=>$this->created_at,
            'user'=>[
                'user_id'=>$this->user->id,
                'user_name'=>$this->user->name,
                'user_address'=>$this->user->address,
                'user_phone'=>$this->user->phone,
            ],
             'order_details'=> OrderdetailResource::collection($this->orderdetails),
             'address_data'=> new buyeraddressesCollection(buyerAddresses::where("id","=",$this->buyeraddresse_id)->get())
        ];
    }
}
