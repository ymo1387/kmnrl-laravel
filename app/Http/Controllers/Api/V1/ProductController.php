<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Filter\V1\ProductFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\V1\ProductResource;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\V1\ProductDetailResource;
use App\Http\Resources\V1\ProductGridCollection;

class ProductController extends Controller
{
    //  Display a listing of the resource.
    public function index(Request $request)
    {
        // get filter queries
        $filter = new ProductFilter();
        $filterItems = $filter->transform($request);

        $products = Product::where($filterItems);
        if(count($products->get()) <= 0) {
            return response()->noContent();
        }
        $products->with([
            'price',
            'tags',
            'instock',
            'mainImage',
            'family']);

        $products = $products->paginate(10)->appends($request->query());
        return new ProductGridCollection($products);
    }

    public function store(StoreProductRequest $request)
    {
        //
    }

    //  Display the specified resource.
    public function show(Product $product)
    {
        $product->load([
            'price',
            'specifications',
            'images',
        ]);

        return new ProductDetailResource($product);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        //
    }

    public function destroy(Product $product)
    {
        //
    }
}
