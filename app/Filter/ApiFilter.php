<?php

namespace App\Filter;

use Illuminate\Http\Request;

class ApiFilter {
    protected $safeParams = [];

    protected $columnMap = [];

    protected $operatorMap = [];

    public function transform(Request $request) {
        $eloQuery = [];

        foreach($this->safeParams as $param => $operators){
            $query = $request->query($param);
            if (!isset($query)) {
                continue;
            }
            $column = $this->columnMap[$param] ?? $param;

            foreach ($operators as $operator) {
                if (isset($query[$operator])) {
                    $value = $operator == 'lk' ? '%'.$query[$operator].'%' : $query[$operator];
                    $eloQuery[] = [$column, $this->operatorMap[$operator], $value];
                }
            }
        }

        return $eloQuery;
    }
}
