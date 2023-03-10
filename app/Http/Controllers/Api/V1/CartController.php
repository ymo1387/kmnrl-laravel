<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CartResource;
use App\Http\Requests\CartUpdateRequest;
use App\Http\Resources\V1\CartCollection;

class CartController extends Controller
{
    private $loadarr = [
            'product:id,name',
            'product.mainImage',
            'product.price',
            'product.instock',
        ];

    //  Display a listing of the resource.
    public function index()
    {
        return new CartCollection(auth()->user()->carts->load($this->loadarr));
    }

    // Create
    public function store(Request $request)
    {
        // check incoming product is optical
        $product = Product::findOrFail($request->id);
        if ($product->type === "opticals") {
            return response()->json([], 404);
        }
        // check product already exist in cart
        $productInCart = collect(auth()->user()->carts)->where('product_id',$request->id)->first();
        // update if exist
        if ($productInCart) {
            if ($productInCart->count < 99) {
                $productInCart->count += 1;
                $productInCart->save();
            }
            return response()->noContent();
        }
        // create new
        $cart = new Cart;
        $cart->user_id = auth()->user()->id;
        $cart->product_id = $request->id;
        $cart->count = 1;
        $cart->save();
        return response()->noContent();
    }

    //  Display specified resource.
    public function show(Cart $cart)
    {
        return new CartResource($cart->loadMissing($this->loadarr));
    }

    // Update
    public function update(CartUpdateRequest $request, Cart $cart)
    {
        $validated = $request->validated();
        // update cart
        $cart->count = $validated['count'];
        $cart->save();

        // get latest data from database
        $product = $cart->product;
        $latestInstock = $product->instock;
        $latestPrice = $product->price;

        // check
        // 204 if no data changed
        if ($latestInstock->instock == $validated['instock']
            && $latestPrice->base == $validated['price']['base']
            && $latestPrice->discount == $validated['price']['discount']) {
            return response()->noContent();
        }
        // specified resource if changed
        return response()->json(['instock'=>$latestInstock,'price'=>$latestPrice]);
        // return $this->show($cart);
    }

    // Delete
    public function destroy(Cart $cart)
    {
        $cart->delete();
        return;
    }
}
