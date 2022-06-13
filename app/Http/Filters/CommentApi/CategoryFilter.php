<?php

namespace App\Http\Filters\CommentApi;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class CategoryFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        if ($value === 'all') {
            $query->whereHas('categories');
        } else {
            $query->whereHas('categories', function ($q) use ($value) {
                $q->where('c_id', $value);
            });
        }
    }
}
