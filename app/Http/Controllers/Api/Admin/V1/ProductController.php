<?php

namespace App\Http\Controllers\Api\Admin\V1;

use App\Models\Image;
use App\Models\Price;
use App\Models\Family;
use App\Models\Instock;
use App\Models\Product;
use App\Models\ProductTag;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Specification;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ProductDetailResource;
use App\Http\Requests\Admin\ProductCreateRequest;
use App\Http\Resources\Admin\V1\ProductCollection;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['instock','price'])->get();
        return new ProductCollection($products);
    }

    public function store(ProductCreateRequest $request)
    {
        return "";
        $product = new Product();
        $product->name = Str::title($request->name);
        $product->slug = Str::slug($request->name,'-');
        $product->type = $request->type;
        $product->description = $request->description
            ? str_replace(array("\r", "\n"), '', $request->description)
            : null;

        // family
        if ($request->family) {
            $existFamily = Family::where(['name'=>$request->family])->first();
            if ($existFamily) {
                $existFamily->count += 1;
                $existFamily->save();
                $product->family_id = $existFamily->id;
            } else {
                $family = new Family();
                $family->name = Str::title($request->family);
                $family->slug = Str::slug($request->family,'-');
                $family->count = 1;
                $family->save();
                $product->family_id = $family->id;
            }
        }

        // product save
        $product->save();

        // price
        if ($product->type === 'opticals') {
            $base = null;
            $discount = null;
        } else {
            $base = number_format($request->price['base'], 2);
            $discount =  $request->price['discount']
                ? number_format($request->price['discount'], 2)
                : null;
        }
        $product->price()->create(['base'=>$base,'discount'=>$discount]);

        // instock
        $product->instock()->create(['instock'=>(int)$request->instock]);

        // tags
        if ($request->tags) {
            foreach($request->tags as $tag) {
                ProductTag::create(['product_id'=>$product->id,'tag_id'=>(int)$tag]);
            }
        }

        // specifications
        if ($request->specifications) {
            $specarray = [];
            $specs = explode("\r\n",$request->specifications);
            foreach ($specs as $k => $v) {
                if (!$v) {
                    continue;
                }
                array_push($specarray, ['index'=>$k,'text'=>$v]);
            }
            $product->specifications()->createMany($specarray);
        }

        // images
        if ($request->hasFile('imgmain')) {
            $image = $request->file("imgmain");
            $this->productImage($image, 'main', $product->id);
        }

        if ($request->imgsec) {
            foreach ($request->imgsec as $image) {
                $this->productImage($image, 'sec', $product->id);
            }
        }

        if ($request->imgother) {
            foreach ($request->imgother as $image) {
                $this->productImage($image, 'other', $product->id);
            }
        }

        return response()->json([],200);
    }

    public function show(Product $product)
    {
        $product->load([
            'price',
            'specifications',
            'images',
        ]);

        return new ProductDetailResource($product);
    }

    public function update(Request $request, Product $product)
    {
        if ($request->family) {
            logger("exist");
        } else {
            logger("no");
        }
        logger($product->price());
        logger($request);

        return "";
        logger($request);
        logger($product);
        if ($request->name) {
            $product->name = Str::title($request->name);
            $product->slug = Str::slug($request->name,'-');
        }
        if ($request->family) {
            $existFamily = Family::where(['name'=>$request->family])->first();
            if ($existFamily) {
                $product->family_id = $existFamily->id;
                $existFamily->count += 1;
                // $existFamily->save();
            } else {
                $family = new Family();
                $family->name = $request->family;
                $family->slug = Str::slug($request->family,'-');
                $family->count = 1;
                // $family->save();
                $product->family_id = $family->id;
            }
        }
        if ($request->type) {
            $product->type = $request->type;
        }

        if ($request->base || $request->discount) {
            if ($product->type === "opticals") {
                $product->price()->base = null;
                $product->price()->discount = null;
            } else {
                $request->base && $product->price()->base = number_format($request->base, 2);
                $request->discount && $product->price()->discount = number_format($request->discount, 2);
            }
        }

        if ($request->description) {
            $product->description = $request->description
            ? str_replace(array("\r", "\n"), '', $request->description)
            : null;
        }
        if ($request->specifications) {
            $product->name = $request->name;
        }

        // $product->isDirty() && $product->save();

        $price = new Price();
        if ($product->type === "opticals") {
            $price->base = 0.00;
            $price->discount = null;
        } else {
            $price->base = number_format($request->price['base'], 2);
            $price->discount = $request->price['discount']
                ? number_format($request->price['discount'], 2)
                : null;
        }
        // $product->price()->save($price);

        logger($product);
        return "";
    }

    public function destroy(Product $product)
    {
        //
    }

    private function productImage($image, $type, $productId) {
        $image_name = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        $image_extension = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
        $image->storeOnCloudinaryAs('kmnrl/products', $image_name);
        Image::create([
            'name' => $image_name,
            'extension' => $image_extension,
            'path' => null,
            'type' => $type,
            'product_id' => $productId]);

        return true;
    }
}
