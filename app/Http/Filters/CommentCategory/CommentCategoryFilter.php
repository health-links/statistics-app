<?php

namespace App\Http\Filters\CommentCategory;

use App\Models\CommentCategory;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Filters\CommentCategory\ToFilter;
use App\Http\Filters\CommentCategory\FromFilter;
use App\Http\Filters\CommentCategory\ClientFilter;
use App\Http\Filters\CommentCategory\ServiceFilter;
class CommentCategoryFilter extends QueryBuilder
{
    // constructor
    public function __construct()
    {
        $data = (new CommentCategory)->query();
        parent::__construct($data);
        $this->allowedFilters([
            AllowedFilter::custom('client_id', new ClientFilter),
            AllowedFilter::custom('service_id', new ServiceFilter),
            AllowedFilter::custom('from', new FromFilter),
            AllowedFilter::custom('to', new ToFilter)
        ]);
    }
}
