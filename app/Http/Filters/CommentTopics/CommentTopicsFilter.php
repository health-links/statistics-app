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
    // counsturctor
    public function __construct()
    {
        $comments_topics = (new CommentTopics)->query();
        parent::__construct($comments_topics);
        $this->allowedFilters([
            AllowedFilter::custom('client_id', new ClientFilter),
            AllowedFilter::custom('service_id', new ServiceFilter),
            AllowedFilter::custom('category', new CategoryFilter),
        ]);
    }
}
