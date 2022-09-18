<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Stripe;
class StripePaymentController extends Controller
{
    public function stripe(){
      return view ("stripe");
    }
    public function stripePost(Request $request){
    Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    Stripe\Charge::create([
        "amount"=>$request->amount,
        "currency"=>"usd",
        "source"=>$request->stripetoken,
        "description"=> "ya rab teshtaghal without errors "
    ]);
    Session::flash('success','payment has been successfuly');
    return back();
    }
}
