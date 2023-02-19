<?php

namespace App\Filter\V1;

use App\Filter\ApiFilter;
use Illuminate\Http\Request;

class ProductFilter extends ApiFilter {
    protected $safeParams = [
        'name' => ['eq','neq','lk'],
        'slug' => ['eq','neq','lk'],
        'type' => ['eq','neq'],
        'instock' => ['eq','neq','lt','lte','gt','gte'],
        'price' => ['eq','neq','lt','lte','gt','gte'],
    ];

    protected $columnMap = [
        'name' => 'slug',
    ];

    protected $operatorMap = [
        'eq' => '=',
        'neq' => '!=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
        'lk' => 'like',
    ];
}
