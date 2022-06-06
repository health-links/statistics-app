<?php

namespace App\Http\Filters\CommentTopics;

use App\Models\CommentTopics;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Filters\CommentTopics\ClientFilter;
use App\Http\Filters\CommentTopics\ServiceFilter;
use App\Http\Filters\CommentTopics\CategoryFilter;

class CommentTopicsFilter extends QueryBuilder
{
    public function __construct()
    {
        $data = (new CommentTopics)->query();
        parent::__construct($data);
        $this->allowedFilters([
            AllowedFilter::custom('service_id', new ServiceFilter),
            AllowedFilter::custom('client_id', new ClientFilter),
            AllowedFilter::custom('category', new CategoryFilter),
        ]);
    }
}
