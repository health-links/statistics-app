<?php

namespace App\Http\Filters\CommentApi;

use App\Models\CommentApi;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Filters\CommentApi\ToFilter;
use App\Http\Filters\CommentApi\FromFilter;
use App\Http\Filters\CommentApi\ClientFilter;
use App\Http\Filters\CommentApi\ServiceFilter;

class CommentApiFilter extends QueryBuilder
{
    // constructor
    public function __construct()
    {
        $comments_api = (new CommentApi)->query();
        parent::__construct($comments_api);
        $this->allowedFilters([
            AllowedFilter::custom('client_id', new ClientFilter),
            AllowedFilter::custom('service_id', new ServiceFilter),
            AllowedFilter::custom('from', new FromFilter),
            AllowedFilter::custom('to', new ToFilter)
        ]);
    }


}
