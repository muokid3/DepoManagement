<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $products= Product::orderBy('id', 'desc')->paginate(10);
        return view('products.products', ['products' => $products]);

    }

    function new_product(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
        ]);

        $name = $request->name;

        DB::transaction(function() use ($name) {
            $product = new Product();
            $product->product_name = $name;
            $product->saveOrFail();
            Session::flash("success", "Product created Successfully!");
        });

        return redirect('/products');
    }

    function product($product_id)
    {
        $product = Product::find($product_id);
        if (is_null($product)){
            abort(404);
        }else{
            return view('products.product')->with('product', $product);
        }
    }

    function update_product(Request $request)
    {
        $product = Product::find($request->product_id);
        if (is_null($product)){
            abort(404);
        }else{
            DB::transaction(function() use ($product, $request) {
                $product->product_name = $request->name;
                $product->update();
                Session::flash("success", "Product updated Successfully!");
            });
            return redirect()->back();

        }
    }
}
