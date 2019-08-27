<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class ProductController extends Controller
{
    public function add()
{
    $product = Product::all();
    return view('admin.product.create', compact('product'));
}

    public function store(Request $request)
    {
    	//validasi data
    	$request->validate([
			'name' =>  ['required', 'string'],
			'price' =>  ['required', 'string'],
			]);

		//menyimpan ke table products kemudian redirect page 
    	$result = Product::create([
			'name' => $request->name,
			'price' => $request->price,
			]);
			

    	return redirect(route('product.add'));
	}

    public function index(){
        $products = Product::all();
        return view('admin.product.index', ['products' => $products]);

	}
    
  

}