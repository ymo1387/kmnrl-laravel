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
    public function list(Request $request)
    {
        // get filter queries
        $filter = new ProductFilter();
        $filterItems = $filter->transform($request);
        // filter products
        $products = Product::where($filterItems);
        if(count($products->get()) <= 0) {
            return response()->noContent();
        }
        // eager load
        $products->with([
            'price',
            'tags',
            'instock',
            'mainImage',
            'family'=>function($query) {
                $query->withCount('products');
            }
        ]);

        $products = $products->paginate(10)->appends($request->query());
        return new ProductGridCollection($products);
    }

    //  Display the specified resource.
    public function detail(Product $product)
    {
        $product->load([
            'price',
            'specifications',
            'images',
            'tags',
        ]);

        return new ProductDetailResource($product);
    }
}
