<?php

namespace App\Http\Filters\CommentApi;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class ServiceFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {

        if ($value !== 'all') {
            return $query->where('sn_service', $value);
        }
        return $query;

    }


}
