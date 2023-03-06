<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_code',
        'total',
        'status'
    ];

    public function status() {
        return $this->belongsTo(Orderstatus::class, 'status', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function details() {
        return $this->hasMany(Orderdetail::class);
    }
}
