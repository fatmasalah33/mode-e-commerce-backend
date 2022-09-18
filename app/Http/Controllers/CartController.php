<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Http\Resources\CartResource;
use App\Http\Resources\CartCollection;
use Illuminate\Support\Facades\DB;
class CartController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return new CartCollection (Cart::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            $cart=new Cart;
            $cart->price=$request->price;
            $cart->quantity=$request->quantity;
            $cart->product_id=$request->product_id;
            $productSize=DB::table('products_size')->where('product_id','=',$request->product_id)->get();
             $cart->size_id=$request->size_id;
           // $cart->user_id=auth()->id();
            $cart->user_id=$request->user_id;
            $cart->save();
            return response()->json("succesfully store", 200);




    //     $cart=new Cart;
    //     $cart->create($request->all());
    //    return response()->json("succesfully store", 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new CartCollection (Cart::where('user_id','=',$id)->get());

    }


    public function calcprice($id){
        $price = DB::table('cart')
             ->select(DB::raw('sum(cart.quantity*cart.price) as totalprice'))
             ->where('user_id', '=', $id)
             ->get();
             return $price;

    }
    public function totalitem($id){
        $count = DB::table('cart')
        ->select(DB::raw('sum(cart.quantity) as count'))
        ->where('user_id', '=', $id)
        ->get();
        return $count ;
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
        $cart=Cart::find($id);
        $cart->update($request->all());
        return response()->json($cart, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        Cart::find($id)->delete();
        return response()->json("deleted is done", 200);
    }

}
