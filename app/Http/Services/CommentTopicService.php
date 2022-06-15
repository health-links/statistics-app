<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;
use App\Traits\HandleFilterRequest;


class CommentTopicService
{
    use HandleFilterRequest;

    private function filterData()
    {
        $data = DB::table('comments_topics')
            ->join('comment_topic', 'comment_topic.topic_id', '=', 'comments_topics.t_id')
            ->join('comments_api', 'comments_api.sn_id', '=', 'comment_topic.comment_id')
            ->when(request()->filter && array_key_exists('category', request()->filter), function ($q) {
                if (request()->filter['category'] != 'all') {
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
            });
        return $data;
    }

    public function getTopicsData()
    {
        $topics = $this->filterData()
            ->select('comment_topic.topic_id', 'comments_topics.t_name', 'comment_topic.type', 'comments_api.sn_id', 'comments_api.sn_client')
            ->selectRaw("-count(CASE when comment_topic.type = 'positive' THEN 1 END) AS positive_count")
            ->selectRaw("count(CASE when comment_topic.type = 'negative' THEN 1 END) AS negative_count")
            ->groupBy('comment_topic.topic_id', 'comments_topics.t_name')
            ->get();

        return $topics;
    }

    public function getCommentsTopic()
    {
        $data =
            $this->filterData()
            ->select('comment_topic.topic_id', 'comments_topics.t_name', 'comment_topic.type', 'comments_api.*')
            ->where('comments_topics.t_name', request()->topic)
            ->where('comment_topic.type', request()->type)
            ->groupBy('comment_topic.topic_id', 'comments_api.sn_id')
            ->get();

        return ['data' => $data];
    }

    public function getTopNegativeTopic()
    {
        $data =
            $this->filterData()
            ->select('comment_topic.topic_id', 'comments_topics.t_name', 'comment_topic.type', 'comments_api.sn_id', 'comments_api.sn_client')
            ->selectRaw("count(CASE when comment_topic.type = 'negative' THEN 1 END) AS negative_count")
            ->groupBy('comment_topic.topic_id', 'comments_topics.t_name')
            ->orderBy('negative_count', 'desc')
            ->limit(10)
            ->get();


        return  $data;
    }


    public function getTopPositiveTopic()
    {
        $data =
            $this->filterData()
            ->select('comment_topic.topic_id', 'comments_topics.t_name', 'comment_topic.type', 'comments_api.sn_id', 'comments_api.sn_client')
            ->selectRaw("count(CASE when comment_topic.type = 'positive' THEN 1 END) AS positive_count")
            ->groupBy('comment_topic.topic_id', 'comments_topics.t_name')
            ->orderBy('positive_count', 'desc')
            ->limit(10)
            ->get();

        return  $data;
    }
}
