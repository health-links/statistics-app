<?php

namespace App\Http\Filters\CommentApi;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FromFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        return $query->where('sn_amenddate','>=', $value);
    }
}
