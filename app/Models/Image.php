<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'extension',
        'path',
        'type',
        'product_id',
    ];

    protected $visible = ['name','extension'];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public $timestamps = false;
}
