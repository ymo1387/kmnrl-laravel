<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Orderdetail extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'count'
    ];
    public $timestamps = false;

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
