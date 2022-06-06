<?php

namespace App\Http\Filters\CommentTopics;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class CategoryFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        if(request()->category === 'all') {
            return  $query->whereHas('comments');
        }
        $query->whereHas('comments', function ($q)  {
            return $q->whereHas('categories', function ($q) {
                return $q->where('category_id', '=', request()->category);
            });
        });

    }
}
