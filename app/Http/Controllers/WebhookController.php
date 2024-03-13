<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;

class WebhookController extends Controller
{

    public function products_create(Request $request){

        $product = $request->all();

        $findproduct = Products::where('product_id', $product['id'])->first();

        if(empty($findproduct)){
            $new_product = new Products();
            $new_product->product_id = $product['id'];
            $new_product->title = $product['title'];
            if(!empty($product['image']['src'])){
                $new_product->image_url = $product['image']['src'];
            }
            $new_product->save();
        }else{
            $findproduct->product_id = $product['id'];
            $findproduct->title = $product['title'];
            if(!empty($product['image']['src'])){
                $findproduct->image_url = $product['image']['src'];
            }
            $findproduct->save();
        }
    }

    public function products_update(Request $request){
        $product = $request->all();

        $findproduct = Products::where('product_id', $product['id'])->first();

        if(empty($findproduct)){
            $new_product = new Products();
            $new_product->product_id = $product['id'];
            $new_product->title = $product['title'];
            if(!empty($product['image']['src'])){
                $new_product->image_url = $product['image']['src'];
            }
            $new_product->save();
        }else{
            $findproduct->product_id = $product['id'];
            $findproduct->title = $product['title'];
            if(!empty($product['image']['src'])){
                $findproduct->image_url = $product['image']['src'];
            }
            $findproduct->save();
        }
    }

    public function products_delete(Request $request){
        $product = $request->all();

        $findproducts = Products::where('product_id', $product['id'])->get();

        foreach($findproducts as $product){
            $product->delete();
        }
    }

}
