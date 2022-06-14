<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;
use App\Traits\HandleFilterRequest;


class CommentTopicService
{
    use HandleFilterRequest;
    public function getTopicsData()
    {
        $topics = DB::table('comments_topics')
            ->join('comment_topic', 'comment_topic.topic_id', '=', 'comments_topics.t_id')
            ->join('comments_api', 'comments_api.sn_id', '=', 'comment_topic.comment_id')
            ->when(request()->filter && array_key_exists('category', request()->filter), function ($q) {
                if ($this->checkFilterParams('category')) {
                    $q->join('comment_category as category', 'category.comment_id', '=', 'comments_api.sn_id')
                        ->where('category.category_id', request()->filter['category']);
                }
                $q->join('comment_category', 'comment_category.comment_id', '=', 'comments_api.sn_id');
            })
            ->when($this->checkFilterParams('client_id'), function ($q) {
                $q->where('comments_api.sn_client', request()->filter['client_id']);
            })
            ->when($this->checkFilterParams('service_id'), function ($q) {
                $q->where('comments_api.sn_service', request()->filter['service_id']);
            })
            ->select('comment_topic.topic_id', 'comments_topics.t_name', 'comment_topic.type', 'comments_api.sn_id', 'comments_api.sn_client')
            ->selectRaw("-count(CASE when comment_topic.type = 'positive' THEN 1 END) AS positive_count")
            ->selectRaw("count(CASE when comment_topic.type = 'negative' THEN 1 END) AS negative_count")
            ->groupBy('comment_topic.topic_id', 'comments_topics.t_name')
            ->get();

        return $topics;
    }
}
