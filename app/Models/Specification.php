<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Specification extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'index',
        'text'
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public $visible = ['text'];
    public $timestamps = false;
}
