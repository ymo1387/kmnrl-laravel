<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Cartmain;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'product_id',
        'count',
    ];

    protected $visible = [
        'id',
        'product_id',
        'count',
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
