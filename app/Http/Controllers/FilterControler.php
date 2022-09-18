<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use DB;

class FilterControler extends Controller
{
    // done
    public function SearchByProductName(Request $request)
    {

    $products=new ProductCollection(Product::whereNotNull('product_verified_at')->get());
    if($request->keyword)
    {
       $products=new ProductCollection(Product::where('name','like','%'.$request->keyword.'%')->whereNotNull('product_verified_at')->get());

    }


   return response()->json( ["data"=>$products], 200);
    }
    public function getId(Request $request)
    {


       $product=Product::where('name','=',$request->name)->value('id');


   return response()->json(["id"=> $product], 200);
    }
    //Search By Category Name
    public function searchByCategoryName(Request $request)
    {
        //    done
    $products=new ProductCollection(Product::whereNotNull('product_verified_at')->get());

    if($request->keyword)
    {
        $categoryId=Category::where('name','like','%'.$request->keyword.'%')->get('id');
        foreach ($categoryId as $key => $value) {
            $products = new ProductCollection(Product::where('category_id','=',$categoryId[$key]->id)->whereNotNull('product_verified_at')->get());

        }


    }
    return response()->json( ["data"=>$products], 200);

    }




    //Filter By Category Name
    public function filterByCategoryName(Request $request)
    {

    $products=new ProductCollection(Product::whereNotNull('product_verified_at')->get());
    $brands=Product::select('brand')->distinct()->get();
    if($request->keyword)
    {
       $products=new ProductCollection(Product::where('category_id','=',$request->keyword)->whereNotNull('product_verified_at')->get());
       $brands=Product::where('category_id','=',$request->keyword)->whereNotNull('product_verified_at')->distinct()->get('brand');

    }

   return response()->json( ["data"=>$products,
"brand"=>$brands], 200);
    }

    public function filterByBrandName(Request $request)
    {
                $items=new ProductCollection(Product::whereNotNull('product_verified_at')->whereIn("category_id",$request->id)->when($request->selected_brands, function ($query, $selected_brands) {
                    return $query->whereIn('brand',$selected_brands);
                })->when($request->price, function ($query, $price)  {
                    return $query->whereBetween('price',[$price['min'],$price['max']]);
                })->get()) ;
                return response()->json( $items, 200);

    }

public function allcat(){
    $cats= Category::whereNotNull('category_id')->get();
    $products=[];
    $all=[];
    $category_id=0;
    $category_name='';
    $y=[];
    foreach ($cats as $cat ) {
        $category_id=$cat->id;
        $category_name=$cat->name;
        $product=new ProductCollection(Product::where('category_id','=',$cat->id)->whereNotNull('product_verified_at')->get());


        array_push($all, ['name'=>$category_name,'id'=>$category_id,'item'=>$product]);
    }
    return response()->json( ["subcat"=>$all], 200);
}
public function allsubcat(){
    $cats= Category::whereNotNull('category_id')->get();
    return response()->json( ["subcat"=>$cats], 200);
}
    // filter by price



    // filter by rating

}
