<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\OfferResource;
use App\Http\Resources\OfferCollection;
use App\Http\Resources\ProductCollection;
use App\Models\Offer;
use App\Models\Product;
use DB;


class OfferController extends Controller
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
       return  new OfferCollection( Offer::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $request->product_id
      $exist_offeres=Offer::where('product_id','=',$request->product_id)->get();
    //   return $exist_offeres;
      foreach ($exist_offeres as $offer) {
        if(now()->diffInDays($offer->end_at))  return  response()->json("there is a current offer for this product");
    }
        $offer=new Offer;
        $offer->create($request->all());
       return response()->json("succesfully store", 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Offer::find($id);

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
        $offer=Offer::find($id);
        $offer->update($request->all());
        return response()->json(new OfferResource($offer), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Offer::find($id)->delete();
        return response()->json("deleted is done", 200);
    }

    public function productOffered(){
        $filters=Offer::where("end_at",'>',now())->get("product_id");
        // return $filters;
         $products_offered=[];
         $arr_indexs=[];
        //  return $filters;
        foreach ($filters as $filter) {
            do{
                $index= random_int(0,count($filters)-1);
              }while( in_array($index,$arr_indexs));
                array_push($arr_indexs,$index);

                $filter=$filters[$index];
                // return $filter;
          $product= new ProductCollection(Product::where('id',"=",$filter->product_id)->where("quantity",">",0)->get()) ;

          array_push($products_offered, $product);

        }
        return $products_offered;

    }


    //   to get offer for each seller
    public function  getAllOffers($id){
        $products=Product::where("user_id","=",$id)->get("id");
        // return $products;
        $product_offered=Offer::whereIn("product_id",$products)->get();
        return  new OfferCollection( $product_offered) ;

    }
    public function endedoffer($id){
        $products=Product::where("user_id","=",$id)->get("id");
        $product_offered=Offer::whereIn("product_id",$products)->where("end_at","<",now())->get();
        return   new OfferCollection( $product_offered) ;
    }
    public function workoffer($id){
        $products=Product::where("user_id","=",$id)->get("id");
        $product_offered=Offer::whereIn("product_id",$products)->where("end_at",">",now())->get();
        return   new OfferCollection( $product_offered) ;
    }



    // flash sale

    // public function endAtTheSameTime(){

    //        $offeres=Offer::
    //        get()
    //        ->groupBy('end_at') ;
    //         // return  $offeres;
    //        $products_offered=[];
    //        $offeress=[];
    //        foreach($offeres  as $offer){
    //          if (count($offer))
    //         array_push($offeress,$offer) ;

    //         // $product= new ProductCollection(Product::where('id',"=",$offer->product_id)->get()) ;
    //         // array_push($products_offered, $product);
    //        }
    // return $offeress;
    // }
}
