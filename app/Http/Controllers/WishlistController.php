<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\WishlistResource;
use App\Http\Resources\WishlistCollection;
use App\Models\Wishlist;


class WishlistController extends Controller
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
        return new WishlistCollection (Wishlist::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $wishlist=new Wishlist;
         $wishlist->product_id=$request->product_id;
         //$wishlist->user_id=auth()->id();
         $wishlist->user_id=$request->user_id;
         $wishlist->save();
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
        return new WishlistCollection (Wishlist::where('user_id','=',$id)->get());

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
        // $wishlist=Wishlist::where('user_id','=',$id)->get();
        // $wishlist->update($request->all());
        // return response()->json($wishlist, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Wishlist::find($id)->delete();
        return response()->json("deleted is done", 200);
    }
}
