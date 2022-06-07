<?php

namespace App\Http\Filters\CommentTopics;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class CategoryFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        if ($value === 'all') {
             $query->whereHas('comments');
        } else {
            $query->whereHas('comments', function ($q) use ($value) {
                 $q->whereHas('categories', function ($q) use ($value) {

                    $q->where('c_id', $value);
                });
            });
        }
    }
}
