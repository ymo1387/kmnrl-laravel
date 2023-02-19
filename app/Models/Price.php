<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Price extends Model
{
    use HasFactory;
    protected $fillable = [
        'base',
        'discount',
        'product_id',
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    protected $visible = ['base','discount'];
}
