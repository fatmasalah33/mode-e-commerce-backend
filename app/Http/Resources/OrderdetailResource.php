<?php

namespace App\Http\Resources;
use DB;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Product;
class OrderdetailResource extends JsonResource
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
            'product_id'=>$this->product_id,
            'order'=>$this->order,
            'product_quantity'=>$this->quantity,
            'product_data'=> new ProductCollection( Product::where('id', '=', $this->product_id)->get()),
            'size_data'=> DB::table('sizes')->where("id","=",$this->size_id)->value('size'),
            'product_name'=> DB::table('products')->where("id","=",$this->product_id)->value('name'),

            // 'product_data'=>ProductResource::collection($this->product),
            //  'order'=>new OrderResource($this->order_id)
        ];
    }
}
