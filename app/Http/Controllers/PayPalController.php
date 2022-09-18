<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\ExpressCheckout;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
class PayPalController extends Controller
{
    public function payment( ){
        $data = [];
        // **************************************************
        // request ={
            // products: from cart,  =>payment & product_details
            // total price : from cart,
            // address & comment & payment_id & copoun : form in checkout


        // }
        // function set in order&order_details => cash on delivery route
        // payment gateway=>redirect=>route for place_order
        // ******************************************************
        //  $data['items']= $request->products;
                 // foreach($product as $item) {
        //     $data['items'].array_push([
        //         'name' => $item['name'],
        //         'price'=>$item.['price'],
        //         "desc"=>$item.['description'],
        //         'qty'=>$item.['quantity']
        //     ]);
        // }
        $data['items'] = [
            [
                'name' => 'Product 1',
                'price' => 100,
                'desc' => 'Description for Product 1',
                'qty' => 1
            ]
        ];

        $data['invoice_id'] = 1;

        $data['invoice_description'] = "Order #{$data['invoice_id']} Invoice";

        $data['return_url'] = route('payment.success');

        $data['cancel_url'] = route('payment.cancel');

        $data['total'] =100;

        $provider = new ExpressCheckout;
        $response = $provider->setExpressCheckout($data);
        $response = $provider->setExpressCheckout($data, true);

         return redirect($response['paypal_link']);

    }

    public function paymentCancel()
    {
        return response()->json( 'Your payment has been declend. The payment cancelation page goes here!');
    }

    public function success(Request $request)
    {
        $paypalModule = new ExpressCheckout;
        $response = $paypalModule->getExpressCheckoutDetails($request->token);

        if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
            return response()->json( 'success') ;
            

        }

        return response()->json( 'something went wrong!') ;
    }

}
