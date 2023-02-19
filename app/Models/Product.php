<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\Image;
use App\Models\Price;
use App\Models\Family;
use App\Models\Instock;
use App\Models\Product;
use App\Models\ProductTag;
use App\Models\Specification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'family_id',
        'type',
        'description',
    ];

    public function family() {
        return $this->belongsTo(Family::class);
    }

    public function price() {
        return $this->hasOne(Price::class);
    }

    public function tags() {
        return $this->belongsToMany(Tag::class, 'product_tags', 'product_id', 'tag_id');
    }

    public function images() {
        return $this->hasMany(Image::class);
    }
    public function mainImage() {
        return $this->hasOne(Image::class)->where('type','main');
    }

    public function instock() {
        return $this->hasOne(Instock::class);
    }

    public function specifications() {
        return $this->hasMany(Specification::class)->orderBy('index');
    }

    public function variants() {
        return Product::where('family_id',$this->family_id)
            ->whereNot(function($q) {
                $q->where('products.id',$this->id);
            })
            ->select('id','slug')->get();
    }

    //  Get the route key for the model.
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
