<?php

namespace App\Http\Controllers;

use App\Http\Resources\buyeraddressesResource;
use Illuminate\Http\Request;
use DB;
use App\Models\buyerAddresses;
use Validator;
class buyerAddressesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator =Validator::make($request->all(),
        [
            'name' => 'required|string|max:255',
            'address_street'=>'required',
            'address_city'=>'required',
            'address_state'=>'required',
            'phone'=>'required|min:11|numeric|regex:/^01[0125][0-9]{8}$/',
            'user_id'=>'required|numeric',
        ],
        [
             'name.required' => 'برجاء ادخال اسم المستخدم',
             'name.string' => 'لابد ان يكون اسم المستخدم بالحروف',
             'address_street.required' =>'please insert name of street',
             'address_city.required' =>'please please select your city',
             'address_state.required' =>'please insert name of street',
             'phone.required' => 'برجاء ادخال رقم الهاتف الخاص بك',
             'phone.unique'=>'رقم الهاتف مسجل بالفعل',
             'phone.regex'=>'لابد ان يبدا هاتفك ب 015,012,011,010',

        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
      $id= DB::table('buyeraddresses')->insertGetId([
        'user_id'=>$request->user_id,
        'address_state'=>$request->address_state,
        'address_city'=>$request->address_city,
        'address_street'=>$request->address_street,
        'name'=>$request->name,
        'phone'=>$request->phone,
        'created_at'=>now(),
            'updated_at'=>now(),

       ]);
       return response()->json($id,200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
          return  buyeraddressesResource::collection(buyerAddresses::where("user_id","=",$id)->get());

        // $addresse=DB::table('buyeraddresses')->where("user_id","=",$id)->get();
        // return $addresse;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
      $address=buyerAddresses::find($id);
      $address->update($request->all());
      return  response()->json("succesfull update", 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('buyeraddresses')->where("id","=",$id)->delete();
        return response()->json("succesfull delete", 200);
    }


    public function getAddressByID($id){
         return buyerAddresses::find($id);
    }

    public function setDefault($id){
        // $address= DB::table('buyeraddresses')->where("id","=",$id)->get();
        buyerAddresses::where("default","=","checked")->update([
            'default' => null,
           ]);
        $address= buyerAddresses::find($id);
        // return  $address;
        $address->update([
            $address->default='checked',
        ]);

         return   response()->json("succesfull ", 200);

    }
}
