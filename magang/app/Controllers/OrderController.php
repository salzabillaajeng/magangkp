<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Product;
use App\Order;
use App\OrderDetail;

class OrderController extends Controller
{
	public function create()
	{
		$products = Product::all();
		// $user = Auth::user();
		// dd($user);
		return view('order', compact('products'));
	}
	
	public function store(Request $request)
	{
		//validasi data
		$request->validate([
			'product_id' =>  ['required', 'string'],
			'name' => ['required', 'string'],
			'phone' =>  ['required', 'string'],
			'identity' =>  ['required', 'string'],
			'date' =>  ['required', 'string'],
		]);

		$user = Auth::user();
		// dd($user->id);
		if (! $request->session()->has('order_id')) {
			$order = Order::create([
				'user_id' => $user->id
			]);
			$request->session()->put('order_id', $order->id);
		} else {
			$order = Order::findOrFail($request->session()->get('order_id'));
		}

		//menyimpan ke table orders kemudian redirect page 
		$result = OrderDetail::create([
			'order_id' => $order->id,
			'product_id' => $request->product_id,
			'name' => $request->name,
			'phone' => $request->phone,
			'identity' => $request->identity,
			'date' => $request->date,
		]);

		return redirect(route('order.index'));
	}

	public function index(Request $request) {
		$total = 0;
		if ($request->session()->has('order_id')) {
			$orderId = $request->session()->get('order_id');
			$order = Order::whereId($orderId)->whereUserId(\Auth::id())->firstOrFail();
			$order_details = collect();
			foreach (OrderDetail::whereOrderId($orderId)->get() as $row) {
				$order_details->push($row);
				$total += $row->product->price;
			}
		} else {
			$order_details = [];
		}
		return view('user.payment', compact('order_details', 'total'));

		
	}

	public function verify(Request $request) {
		$total = 0;
		if ($request->session()->has('order_id')) {
			$orderId = $request->session()->get('order_id');
			$order = Order::whereId($orderId)->whereUserId(\Auth::id())->firstOrFail();
			$order_details = collect();
			foreach (OrderDetail::whereOrderId($orderId)->get() as $row) {
				$order_details->push($row);
				$total += $row->product->price;
			}
			$request->session()->forget('order_id');
		} else {
			$order_details = [];
		}
		return view('user.verify', compact('order_details', 'total'));
	}

	
	public function destroy($id)
	{
        $order_detail = OrderDetail::whereId($id)->firstOrFail();
        if ($order_detail->delete()) {
            session()->flash('status', ' Succesfully deleted.');
            session()->flash('status-type', 'Success');
        } else {
            session()->flash('status', 'Something was wrong, please try again later.');
            session()->flash('status-type', 'danger');
        }
        return redirect()->route('order.index');
    }


}