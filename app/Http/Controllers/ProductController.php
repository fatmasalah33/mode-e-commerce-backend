<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Order;
use App\Models\orderdetails;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\DB as FacadesDB;

class ProductController extends Controller
{
    // public function __construct()
    // {
    //    $this->middleware('verified');
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::id()==1) return new ProductCollection(Product::all());
        else
         return new ProductCollection(Product::where('quantity','>',0)->get());

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)

    {
        // return $request;
        $file=$request->file('image');
        $upload_path="public/image";
       $originalName= $file->getClientOriginalName();
       $file->move($upload_path,$originalName);
         $product=new Product;
         $product->name=$request->name;
         $product->price=$request->price;
         $product->description=$request->description;
         $product->brand=$request->brand;
         $product->quantity=$request->quantity;
         $product->category_id=$request->category_id;
         $product->user_id=$request->user_id;
        $role_id =User::where('id','=',$request->user_id)->value('role_id');
        if($role_id==1) $product->product_verified_at=now();
         $product->image=$originalName;
         $product->save();
         $parentCat=DB::table('categories')->where('id','=',$request->category_id)->value('category_id');
         $parentCatSizes=DB::table('sizes')->where('category_id','=',$parentCat)->get();
         if(count($parentCatSizes)){
         foreach($request->sizes as $size){
            // return  $size;
            $objSize =json_decode($size,true);
        //    json_decode($request->stdin, true);
        //    $objSize=json.stringf($size) ;
        // return $objSize  ;
            DB::table('products_size')->insert(
                ['product_id'=>$product->id,
                  'size_id'=>$objSize['size'],
                  'quantity'=>$objSize['quantity'],
                  'created_at'=>now(),
                  'updated_at'=>now()
                ]
            );
         }}
         return response()->json("succesfull stor", 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new ProductResource(Product::find($id));
    }
  public function producterbyuser($id){
    return new ProductCollection(Product::where('user_id','=',$id)->get());

  }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductUpdateRequest $request, $id)
    {
        $product=Product::find($id);
        $product->update($request->all());
        return response()->json(new ProductResource($product), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $orders=DB::table('orderdetails')->where('product_id','=',$id)->get('order_id');

        if(count($orders)){
            foreach ($orders as $order) {
                // return $order;
                $order_ids=DB::table('orders')->where("id","=",$order->order_id)->whereIn("status",['confirmed','pending'])->get();
                if(count($order_ids))  return response()->json("this product cannot be deleted", 403);
                else {
                    Product::find($id)->delete();
                    return response()->json("deleted is done", 200);
                }
            }

        }
    else{
        Product::find($id)->delete();
        return response()->json("deleted is done", 200);
    }

    }





    //  to return not verified products for admin
     public function notVerifiedProducts(){
        return new ProductCollection(Product::wherenull('product_verified_at')->get());
     }




  // to enable admin to verify certain product with id of product
    public function verifyProduct( $id ){
        $product=Product::find($id);
        $product->update([$product->product_verified_at=now()]);
        return response()->json("verification is done", 200);
    }

 //  to return not verified products for admin  for each seller
 public function notVerifiedProduct_seller($id){
    return new ProductCollection(Product::wherenull('product_verified_at')->where('user_id','=',$id)->get());
 }


 //  to return  verified products for admin  for each seller
 public function VerifiedProduct_seller($id){
    return new ProductCollection(Product::whereNotNull('product_verified_at')->where('user_id','=',$id)->get());
 }



//  to get best seller products
  public function bestSeller(){
    $counts = DB::table('orderdetails')->select(DB::raw('count(*) as selling_count, product_id'))
    ->groupBy('product_id')
    ->get();
    // return $counts;
    $product_ids=[];
    foreach ( $counts as  $count )
    {
        if( $count->selling_count >2)
        {
            array_push($product_ids, new ProductCollection(Product::where('id','=',$count->product_id)->where("quantity",">",0)->get()));

        }
    }

    return $product_ids;
  }



  //  to get related product

  public function relatedProduct($id){

    $products= new ProductCollection(Product::whereNotNull('product_verified_at')->where('category_id','=',$id)->get());
    $related_products=[];
    $arr_indexs=[];
    foreach($products as $product){
        do{
      $index= random_int(0,count($products)-1);
    }while( in_array($index,$arr_indexs));
      array_push($arr_indexs,$index);
      $product=$products[$index];
      if(count( $related_products)<6){
        array_push($related_products, $product);
      }
    }
        return $related_products;
    }


    //  to get random product
    public function randomProduct(){
        $products= new ProductCollection(Product::whereNotNull('product_verified_at')->where("quantity",">",0)->get());
        // return  $products;
        $random_products=[];
        $arr_indexs=[];
        foreach($products as $product){
            // return $product->category_id;
            do{
          $index= random_int(0,count($products)-1);
        }while( in_array($index,$arr_indexs));
          array_push($arr_indexs,$index);
          $product=$products[$index];
          if(count( $random_products)<6){
            array_push($random_products, $product);
          }
        }
            return $random_products;
        }

    // to get finance of seller
    public function getMoney($id) {
          $products=Product::where("user_id","=",$id)->get("id");
          // return $products;
          $order_ids=Order::where("status","=","delivered")->get("id");
          // return $order_ids;
          $order_details=orderdetails::whereIn("order_id",$order_ids)->whereIn("product_id", $products)->get();
          $totla_money=0;
          foreach($order_details as $product){
            $totla_money+=$product->quantity * ($product->price - (15* $product->price)/100);

          }
          return  response()->json(
           [ "order_details" =>  $order_details,
             "totla_money" => $totla_money

          ], 200);

    }

     //  to get recent viewd product


    }








