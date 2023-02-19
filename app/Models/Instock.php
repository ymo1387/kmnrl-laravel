<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Instock extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'instock',
    ];

    protected $visible = ['instock'];

    public function productInstock() {
        return $this->belongsTo(Product::class);
    }
}
