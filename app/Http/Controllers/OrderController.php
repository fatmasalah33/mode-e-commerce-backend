<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\Order;
use App\Models\orderdetails;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Validator;
class OrderController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
          return  OrderResource::collection( Order::all()) ;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
                 //validation
                 $validator =Validator::make($request->all(),
                 [
                    //  'name' => 'required|string|max:255',
                    //  'address_street'=>'required',
                    //  'address_city'=>'required',
                    //  'address_state'=>'required',
                    //  'phone'=>'required|min:11|numeric|unique:users|regex:/^01[0125][0-9]{8}$/',
                    //  'price'=>'required|numeric',
                    // //  'user_id'=>'required|numeric',
                    // 'buyeraddresse_id'=>'required|numeric',
                    //  'copoun'=>'numeric',
                 ], [
                    //  'name.required' => 'برجاء ادخال اسم المستخدم',
                    //  'name.string' => 'لابد ان يكون اسم المستخدم بالحروف',
                    //  'address_street.required' =>'please insert name of street',
                    //  'address_city.required' =>'please please select your city',
                    //  'address_state.required' =>'please insert name of street',
                    //  'phone.required' => 'برجاء ادخال رقم الهاتف الخاص بك',
                    //  'phone.unique'=>'رقم الهاتف مسجل بالفعل',
                    //  'phone.regex'=>'لابد ان يبدا هاتفك ب 015,012,011,010',
                     'price.required'=>'price is required',
                     'price.numeric'=>'price should be number',
                     'user_id.numeric'=>'user_id should be number',
                     'user_id.required'=>'user_id is required',
                     'buyeraddresse_id.numeric'=>'user_id should be number',
                     'buyeraddresse_id.required'=>'user_id is required',
                    //  'copoun.numeric'=>'copoun should be number',

                 ]);
             if ($validator->fails()) {
                 return response()->json(['error'=>$validator->errors()], 401);
             }
    //  DB::beginTransaction();
    // try{
        // $order->status=$request->status;
        // $order->address_state=$request->address_state;
        // $order->address_city=$request->address_city;
        // $order->address_street=$request->address_street;
        // $order->name=$request->name;
        // $order->phone=$request->phone;
        // $order->comment=$request->comment;
        // $order->payment_id=$request->payment_id;
        $order = new Order();
        $order->buyeraddresse_id=$request->buyeraddresse_id;
        $order->user_id=$request->user_id;
        if ($request->copoun){
            $exist_user= DB::table('copouns')->where("user_id","=",$request->user_id)->where("copoun","=",$request->copoun)->where("status","=","available")->where("end_at",">",now())->get() ;
            if(count($exist_user)!=0){
                $order->copoun=$request->copoun;
                $order->price=$request->price;
                DB::table('copouns')->where("id","=",$exist_user[0]->id)->update(['status'=>'notavailable']);
            }
            else {
                $price=$request->price;
                $order->price= $price +30 ;
            }
        }
        $order->save();
        $order_id=$order->id;
        $items= Cart::where('user_id','=',$request->user_id)->get();
        foreach( $items as $item ){
            $orderItem = new orderdetails;
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $item['product_id'];
            $orderItem->quantity = $item['quantity'];
            $orderItem->price = $item['price'];
            $orderItem->size_id = $item['size_id'];
            $orderItem->save();
            // Product::where('id',"=", $item['product_id'])->update(
            //     [
            //         $this->quantity;
            //     ]
            // )

        }
        Cart::where('user_id','=',$request->user_id)->delete();
    //   DB::commit();
    // }catch (\Exception $e ){
    //     DB::rollBack();
    // }

    return response()->json( $order_id,200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new OrderResource( Order::find($id)) ;
        // return Order::find($id);
    }
    public function showorderofuser($user_id){

        return OrderResource::collection(Order::where('user_id', '=', $user_id)->where('status','=','pending')->get());
    }
    public function showclosedorder($user_id){

        return OrderResource::collection(Order::where('user_id', '=', $user_id)->where('status','!=','pending')->where('status','!=','not completed')->get());
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order=Order::find($id);
        // if($order->status!="done"){
        //     $order->status=$request->status;
        //    return response()->json(new OrderResource($order), 200);
        //     }
        if($request->status=='not completed' &&  $request->payment_id){
            $order->update(
                [
                    $order->status="pending",
                    $order->payment_id=$request->payment_id,

               ]);

            // sellerArr=[
            //     {
            //         "email"=>"ccccccccccc"",
            //         "orderdetails=>",""
            //     }
            // ]
               $sellerArr=[];
              $array_seller=[];
          $product_ids=orderdetails::where("order_id","=",$id)->distinct()->get('product_id');
          foreach($product_ids as $product_id){
            // foreach product -> get its seller
               $ordered_products =Product::where("id","=",$product_id->product_id)->get();
            //  return $ordered_products;
          $role_id=User::where('id','=',$ordered_products[0]->user_id)->value('role_id');
          if($role_id==2) {
              $seller_email=User::where('id','=',$ordered_products[0]->user_id)->get();
        if(in_array($ordered_products[0]->user_id , $array_seller)) break;
        else {
            array_push($array_seller,$ordered_products[0]->user_id);
           $all_product=Product::where("user_id","=",$ordered_products[0]->user_id)->get('id');
           $order_details=  orderdetails::where("order_id","=",$id)->whereIn("product_id", $all_product)->get();
           array_push($sellerArr,['seller_data'=>$seller_email ,
                 'order_details'=>$order_details
            ]);
        }
        }

          }
        // return   $sellerArr;
          foreach($sellerArr as $seller){
        // return  $seller;
            $subject = "hello, congratulations another order are requested from you .";
            $email=$seller['seller_data'][0]->email;
            $name=$seller['seller_data'][0]->name;
            $details=$seller['order_details'];

            Mail::send('order', ['name' =>$name , 'details'=>$details ],
                function($order) use ( $subject,$email,$name,$details){
                    $order->from('gradproj763@gmail.com', "From Moda");
                    $order->to($email, $name);
                    $order->subject($subject);
                });
                // return  $details;
        //     // send email
          }

        return $sellerArr;
        }
        if($request->status =='pending' && $order->payment_id ){
            $order->update([$order->status="confirmed"]);
               $items=DB::table('orderdetails')->where("order_id","=",$id)->get();

               foreach($items as $item){
                $id= $item->product_id;
                $product= Product::find($id);

                $qty=$item->quantity;
                $product->update([
                $product->quantity -=$qty
             ]);  
        //    $update_product=DB::table('products_size')->where('product_id',"=", $item->product_id)->where("size_id","=", $item->size_id)->first();
        //     $x=$update_product->quantity;
        //    $update_product->update([
        //     'quantity' => $x - $qty,
        //    ]);

            // foreach($product_size as $product_s){
            //     $qty=$item->quantity;
            //     $product_s->update([
            //         $product_s->quantity -= $qty
            //  ]);

            //    }
        }
    }
        elseif($request->status=="confirmed" && $order->payment_id ){
            $order->update([$order->status="shipped"]);
        }
        elseif($request->status=="shipped" && $order->payment_id ){
            $order->update([$order->status="delivered"]);
        }

        return response()->json(new OrderResource($order), 200);
        // return response()->json(["message=>not allow to update status  order"], 403);
    }
    public function cancelldorder( $id ){
        $order=Order::find($id);
        $order->update([$order->status='cancelld']);
        return response()->json("order cancelld is done", 200);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response()->json(["message=>not allow to delete order"], 403);
    }

//    for getting copoun
     public function  getCopoun(Request $request){
        $copoun=random_int(100000, 999999);//Generate copoun
        $id=$request->user_id;
       $exist_user=DB::table('copouns')->where('user_id','=',$id)->get();
       if( (count($exist_user)==0) || ( (count($exist_user)!=0 ) && ( now() > $exist_user[count($exist_user)-1]->end_at) )   ) {
        DB::table('copouns')->insert(['user_id'=>$id,'copoun'=>$copoun,'created_at'=>now(),'updated_at'=>now()]);
        $subject = " your copoun to get free shipping  from Lorem.";
        $email=$request->email;
        $name=$request->name;
        Mail::send('copounMaile', ['name' =>$name , 'copoun'=>$copoun],
            function($mail) use ( $subject,$email,$name){
                $mail->from('gradproj763@gmail.com', "From Lorem");
                $mail->to($email, $name);
                $mail->subject($subject);
            });
            return response()->json(["message"=>"email is sent successfully"],200);
       }
       return   response()->json(["message"=>"not allowed yet ,plaese wait for next month "],401);

    }

public function filterbystatus(Request $request){
    $order_ids=Order::where("status","=",$request->status)->get();
    return OrderResource::collection($order_ids);

}

    // public function trackingOrder(Request $request){
    //     Order::where('order_id', '=',$request->order_id)->get();
    //     $items=orderdetails::where('order_id', '=',$request->order_id)->get();
    // }
}
