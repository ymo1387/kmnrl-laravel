<?php

namespace App\Http\Controllers\Api\V1;

use App\Mail\Ordered;
use App\Models\Order;
use App\Models\Orderdetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->orders->load('status');
        return response()->json($orders, 200);
    }

    public function store(Request $request)
    {
        // parent order
        $order = new Order;
        $order->user_id = $request->user()->id;
        $order->order_code = mt_rand(10000,99999).uniqid();
        $order->total = $request->total;
        $order->status = 1;
        $order->save();
        $orderId = $order->id;
        // order details
        $carts = $request->user()->carts;
        $orderdetails = collect($carts)->map(function($i) use($orderId) {
            return [
                'order_id' => $orderId,
                'product_id' => $i->product_id,
                'count' => $i->count,
            ];
        });
        Orderdetail::insert($orderdetails->all());
        // carts delete
        $carts->each(function ($cart) {
            $cart->delete();
        });
        // send mail
        // $mailorder =  $order->loadMissing(['user:id,username','details.product:id,name'])->loadMissing('details.product.price');
        // Mail::to($request->user()->email)->send(new Ordered($mailorder));

        return response()->noContent();
    }

    public function show(Order $order)
    {
        return $order->details->load('product');
    }

    public function update(Request $request, Order $order)
    {
        //
    }
}
