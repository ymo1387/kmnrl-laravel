<?php

namespace App\Http\Controllers\Api\Admin\V1;

use App\Models\Image;
use App\Models\Family;
use App\Models\Product;
use App\Models\ProductTag;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
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
                $product->family_id = $existFamily->id;
            } else {
                $family = new Family();
                $family->name = Str::title($request->family);
                $family->slug = Str::slug($request->family,'-');
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
            $specs = explode("|", str_replace(array("\r", "\n"), '', $request->specifications));
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
        //
    }

    public function update(Request $request, Product $product)
    {
        if ($request->name) {
            $product->name = Str::title($request->name);
            $product->slug = Str::slug($request->name,'-');
        }

        if ($request->family) {
            $existFamily = Family::where(['name'=>$request->family])->first();
            if ($existFamily) {
                $product->family_id = $existFamily->id;
            } else {
                $name = Str::title($request->family);
                $slug = Str::slug($request->family,'-');
                $family = $product->family()->create(['name'=>$name,'slug'=>$slug]);
                $product->family_id = $family->id;
            }
        }

        if ($request->type) {
            $product->type = $request->type;
            $request->type === "opticals" && $product->price()->update(['base'=>null, 'discount'=>null]);
        }

        if ($request->hasAny(['base','discount'])) {
            if ($product->type === "opticals") {
                $product->price()->update(['base'=>null, 'discount'=>null]);
            } else {
                $request->base &&
                    $product->price()->update(['base'=>number_format($request->base, 2)]);

                if ($request->has('discount') && $request->discount !== null) {
                    $pricediscount = (int)$request->discount === 0
                        ? null
                        : number_format($request->discount, 2);
                    $product->price()->update(['discount'=>$pricediscount]);
                }
            }
        }

        $request->has('instock')
            && $request->instock !== null
            && $product->instock()->update(['instock'=>(int)$request->instock]);

        if ($request->has("tags") && !empty($request->tags)) {
            foreach($request->tags as $tag) {
                // ProductTag::create(['product_id'=>$product->id,'tag_id'=>(int)$tag]);
            }
        }

        if ($request->description) {
            $product->description = $request->description
            ? str_replace(array("\r", "\n"), '', $request->description)
            : null;
        }

        if ($request->specifications) {
            $product->specifications()->delete();
            $specarray = [];
            $specs = explode("|", str_replace(array("\r", "\n"), '', $request->specifications));
            foreach ($specs as $k => $v) {
                if (!$v) {
                    continue;
                }
                array_push($specarray, ['index'=>$k,'text'=>$v]);
            }
            $product->specifications()->createMany($specarray);
        }

        $product->isDirty() && $product->save();
        return response()->json([],200);
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
