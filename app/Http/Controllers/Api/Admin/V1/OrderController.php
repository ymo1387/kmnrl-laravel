<?php

namespace App\Http\Controllers\Api\Admin\V1;

use App\Models\Order;
use App\Models\Orderstatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with([
            'status',
            'details.product:id,name',
            ])->get();
        return response()->json($orders, 200);
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Order $order)
    {
        //
    }

    public function update(Request $request, Order $order)
    {
        $status = Orderstatus::findOrFail((int)$request->orderstatus);
        $order->status()->associate($status);
        $order->save();
        return response()->json(["status" => $status->name], 200);
    }

    public function destroy(Order $order)
    {
        //
    }
}
