<?php

namespace App\Http\Filters\CommentTopics;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class ServiceFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->whereHas('comments', function ($q) use ($value) {
            return $q->where('sn_service', '=', $value);
        });

    }
}
