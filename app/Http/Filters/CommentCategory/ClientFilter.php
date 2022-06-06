<?php
namespace App\Http\Filters\CommentCategory;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class ClientFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {

        $query->whereHas('comments', function ($q) use ($value) {
            return $q->where('sn_client', '=', $value);
        });
    }
}
