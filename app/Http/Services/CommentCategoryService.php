<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;
use App\Http\Filters\CommentCategory\CommentCategoryFilter;

class CommentCategoryService
{
    public function getCommentCategory()
    {
        return (new CommentCategoryFilter());
    }

    public function getCategoriesData()
    {
        $categories = $this->getCommentCategory()
            ->join('comment_category', 'comment_category.category_id', '=', 'comments_categories.c_id')
            ->join('comments_api', 'comments_api.sn_id', '=', 'comment_category.comment_id')
            ->select('comment_category.category_id', 'comments_categories.c_name as c_name', 'comments_api.sn_id')
            ->selectRaw("count(CASE WHEN comments_api.sn_id = comment_category.comment_id and comments_api.r_rate = 'positive' THEN 1 END) AS positive_count")
            ->selectRaw("count(CASE WHEN comments_api.sn_id = comment_category.comment_id and comments_api.r_rate = 'negative' THEN 1 END) AS negative_count")
            ->selectRaw("count(CASE WHEN comments_api.sn_id = comment_category.comment_id and comments_api.r_rate = 'neutral' THEN 1 END) AS neutral_count")
            ->groupBy('comments_categories.c_id', 'comments_categories.c_name')
            ->get();

        // $categories = HelperController::getCommentCategory()->whereHas('comments')->select()->withCount(['negative', 'positive', 'neutral'])->get();
        return $categories;
    }
    public function getHeatMapData()
    {
        $categories = DB::table('comments_categories')
            ->join('comment_category', 'comment_category.category_id', '=', 'comments_categories.c_id')
            ->when(request()->filter && request()->filter['client_id'], function ($q) {
                $q->join('comments_api as comments_Client', 'comments_Client.sn_id', '=', 'comment_category.comment_id')
                    ->where('comments_Client.sn_client', request()->filter['client_id']);
            })
            ->when(request()->filter && request()->filter['service_id'], function ($q) {
                $q->join('comments_api as comments_service', 'comments_service.sn_id', '=', 'comment_category.comment_id')
                    ->where('comments_service.sn_service', request()->filter['service_id']);
            })
            ->join('comment_topic', 'comment_topic.comment_id', '=', 'comment_category.comment_id')
            ->join('comments_topics', 'comments_topics.t_id', '=', 'comment_topic.topic_id')
            ->select('comments_categories.c_name as category_name', 'comment_category.comment_id', 'comments_topics.t_name as topic_name', 'comment_topic.topic_id', DB::raw('count(comment_topic.topic_id) as count'))
            ->groupBy('comments_categories.c_name', 'comments_topics.t_name', 'comment_topic.topic_id')
            ->get();

        return $categories;
    }
}
