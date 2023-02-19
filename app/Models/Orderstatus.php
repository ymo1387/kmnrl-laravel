<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orderstatus extends Model
{
    protected $fillable = [
        'name',
        'info'
    ];
    public $timestamps = false;
}
