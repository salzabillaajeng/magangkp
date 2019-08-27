<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\OrderDetail;

class OrderDetailController extends Controller
{
    public function index(){
        $orders = Order::all();
        return view('admin.order.index', compact('orders'));

    }
    
    public function detail(Request $request) {
		$total = 0;
        $orderId = $request->id;
        $order = Order::findOrFail($orderId);
        $order_details = collect();
        foreach (OrderDetail::whereOrderId($request->id)->get() as $row) {
            $order_details->push($row);
            $total += $row->product->price;
        }
		return view('admin.order.detail', compact('order_details', 'total'));

		
    }
    
    public function lunas(Request $request){
        $orderId = $request->id;
        $order = Order::findOrFail($orderId);
        $order->status= 'Lunas';
        $order->save();
        return redirect('admin/order/index');
    }

}
