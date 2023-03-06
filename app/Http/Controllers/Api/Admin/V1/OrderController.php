<?php

namespace App\Http\Controllers\Api\Admin\V1;

use App\Models\Order;
use App\Models\Orderstatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    public function index()
    {
        if (Gate::denies('is-admin')) {
            return response()->json(['message'=>"unauthorized"], 403);
        }
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
        if (Gate::denies('is-admin')) {
            return response()->json(['message'=>"unauthorized"], 403);
        }
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
