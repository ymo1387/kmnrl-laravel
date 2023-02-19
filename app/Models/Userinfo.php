<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Userinfo extends Model
{
    use HasFactory;
    protected $fillable = [
        'address',
        'phone',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
