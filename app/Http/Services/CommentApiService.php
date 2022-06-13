<?php

namespace App\Http\Services;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\HelperController;
use App\Http\Filters\CommentApi\CommentApiFilter;

class CommentApiService
{
    private function getCommentApi()
    {
        return (new CommentApiFilter());
    }
    public function getComments()
    {
        $data = $this->getCommentApi()
            ->with(['topics', 'categories', 'client'])
            ->latest('sn_amenddate')
            ->paginate(100);
        $tableData = [];
        $data->map(function ($item, $key) use (&$tableData) {
            $tableData[$key] = [
                'id' => $item->sn_id,
                'client' => isset($item->client) ? $item->client->c_acronym : "",
                'categories' => $item->categories->pluck('c_name')->implode(', '),
                'comment' => $item->sn_comment,
                'flag' => $item->flagged,
                'bookmark' => $item->bookmarked,

            ];
            $item->topics->map(function ($topic) use (&$tableData, $item, $key) {
                $tableData[$key]['topics'][] = [
                    't_id' => $topic->t_id,
                    't_name' => $topic->t_name,
                    't_type' => $topic->pivot->type
                ];
            });
        });
        return ['data' => $tableData];
    }



    public function getOverAllComments()
    {
        return $this->getCommentApi()
            ->selectRaw("count(CASE when r_rate = 'positive' THEN 1 END) AS positive")
            ->selectRaw("count(CASE when r_rate = 'negative' THEN 1 END) AS negative")
            ->selectRaw("count(CASE when r_rate = 'neutral' THEN 1 END) AS neutral")
            ->selectRaw("count(CASE when r_rate = 'mixed' THEN 1 END) AS mixed")
            ->first();
    }

    public function getChunksData()
    {
        return $this->getCommentApi()->join('chunks', 'chunks.sn_id', '=', 'comments_api.sn_id')
            ->selectRaw("COUNT(CASE WHEN comments_api.sn_id = chunks.sn_id and chunks.ch_rate = 'positive' THEN 1 END) AS positive")
            ->selectRaw("count(CASE WHEN comments_api.sn_id = chunks.sn_id and chunks.ch_rate = 'negative' THEN 1 END) AS negative")
            ->selectRaw("COUNT(CASE WHEN comments_api.sn_id = chunks.sn_id and chunks.ch_rate = 'neutral' THEN 1 END) AS neutral")
            ->first();
    }
    public function getDataMonthly()
    {
        $start_date = Carbon::now()->subMonth(11);
        $end_date = Carbon::now();
        $period = collect(CarbonPeriod::create($start_date, '1 month', $end_date))->map(function ($date) {
            return $date->format('Y-m-d');
        })->toArray();
        $commentsMonthly = $this->getCommentApi()->whereBetween('sn_amenddate', [$start_date, $end_date])
            ->select(DB::raw('sn_year as year'), DB::raw('sn_month as month'), 'r_rate', DB::raw('count(*) as count'))
            ->where('r_rate', '!=', 'mixed')
            ->orderBy('month', 'asc')
            ->groupBy('year', 'month', 'r_rate')
            ->get();
        $trendChartData = HelperController::trendHandelArr('monthly', $period, $commentsMonthly);
        return $trendChartData;
    }
    public function getDataQuarterly()
    {
        $start_quarter = Carbon::now()->subQuarter(5);
        $end_quarter = Carbon::now();
        $period = collect(CarbonPeriod::create($start_quarter, '1 quarter', $end_quarter))->map(function ($date) {
            return $date->format('Y-m-d');
        })->toArray();

        $commentsQuarterly = $this->getCommentApi()->whereBetween('sn_amenddate', [$start_quarter, $end_quarter])
            ->select(DB::raw('sn_year as year'), DB::raw('sn_quarter as quarter'), 'r_rate', DB::raw('count(*) as count'))
            ->where('r_rate', '!=', 'mixed')
            ->orderBy('quarter', 'asc')
            ->groupBy('quarter', 'r_rate')
            ->get();

        $trendChartData = HelperController::trendHandelArr('quarterly', $period, $commentsQuarterly);
        return $trendChartData;
    }
    public function getDataYearly()
    {
        $start_year = Carbon::now()->subYears(3);
        $end_year = Carbon::now();
        $period = collect(CarbonPeriod::create($start_year, '1 year', $end_year))->map(function ($date) {
            return $date->format('Y');
        })->toArray();

        $commentsYearly = $this->getCommentApi()->whereBetween('sn_amenddate', [$start_year, $end_year])
            ->select(DB::raw('sn_year as year'), 'r_rate', DB::raw('count(*) as count'))
            ->where('r_rate', '!=', 'mixed')
            ->groupBy('year', 'r_rate')
            ->get();
        $trendChartData = HelperController::trendHandelArr('yearly', $period, $commentsYearly);

        return $trendChartData;
    }


    public function updateFlag()
    {
        $comment = $this->getCommentApi()->findOrFail(request()->id);
        $comment->flagged = !$comment->flagged;
        $comment->save();
        return response()->json(["status" => 'success', 'message' => 'Comment flagged successfully']);
    }
    public function updateBookmark()
    {
        $comment = $this->getCommentApi()->findOrFail(request()->id);
        $comment->bookmarked = !$comment->bookmarked;
        $comment->save();
        return response()->json(["status" => 'success', 'message' => 'Bookmark updated']);
    }


    // tables when click on chart
    //1-overall statistics
    public function getCommentsTypes()
    {
        $data = $this->getCommentApi()
            ->where('r_rate', request()->type)
            ->with(['topics', 'categories', 'client'])
            ->latest('sn_amenddate')
            ->get();
        $tableData = [];
        $data->map(function ($item, $key) use (&$tableData) {
            $tableData[$key] = [
                'id' => $item->sn_id,
                'r_rate' => $item->r_rate,
                'client' => isset($item->client) ? $item->client->c_acronym : "",
                'categories' => $item->categories->pluck('c_name')->implode(', '),
                'comment' => $item->sn_comment,
            ];
            $item->topics->map(function ($topic) use (&$tableData, $item, $key) {
                $tableData[$key]['topics'][] = [
                    't_id' => $topic->t_id,
                    't_name' => $topic->t_name,
                    't_type' => $topic->pivot->type
                ];
            });
        });
        return ['data' => $tableData];
    }

    // 2-catagories statistics
    public function getCommentsChunks()
    {
        $data = $this->getCommentApi()
            ->join('chunks', 'chunks.sn_id', '=', 'comments_api.sn_id')
            ->select('comments_api.*', 'chunks.ch_id', 'chunks.ch_rate')
            ->where('chunks.ch_rate', request()->type)
            ->groupBy('chunks.ch_rate', 'chunks.ch_id')
            ->get();
        return ['data' => $data];
    }

    // 3-tree statistics
    public function getCommentsTrend()
    {

        if (request()->period == 'monthly') {
            $monthNumber = Carbon::parse(request()->date)->format('m');
            $month = strrpos($monthNumber, 0) == 0  ? substr($monthNumber, 1) : $monthNumber;
            $year = Carbon::parse(request()->date)->format('Y');
            $comments = $this->getCommentApi()->where('sn_month', $month)->where('sn_year', $year)->where('r_rate', request()->type)->get();
        } elseif (request()->period == 'quarterly') {
            $quarter = substr(strtok(request()->date, '-'), 1);
            $year = '20' . substr(request()->date, strpos(request()->date, "_") + 3);
            $comments = $this->getCommentApi()->where('sn_quarter', $quarter)->where('sn_year', $year)->where('r_rate', request()->type)->get();
        } elseif (request()->period == 'yearly') {
            $year = Carbon::parse(request()->date)->format('Y');
            $comments = $this->getCommentApi()->where('sn_year', $year)->where('r_rate', request()->type)->get();
        }
        return ['data' => $comments];
    }
    //4-categories statistics
    public function getCommentsCategory()
    {
        $data = $this->getCommentApi()->whereHas('categories', function ($query) {
            $query->where('c_name', request()->category);
        })->where('r_rate', request()->type)->get();

        return ['data' => $data];
    }

    //5-topics statistics
    public function getCommentsTopic()
    {
        $data = $this->getCommentApi()->whereHas('topics', function ($query) {
            $query->where('t_name', request()->topic)->where('type', request()->type);
        })->get();

        // $data = DB::table('comments_api')
        //     ->join('comment_topic', 'comment_topic.comment_id', '=', 'comments_api.sn_id')
        //     ->join('comments_topics', 'comments_topics.t_id', '=', 'comment_topic.topic_id')
        //     ->when(request()->filter && array_key_exists('category', request()->filter), function ($q) {
        //         if (request()->filter['category'] != 'all') {
        //             $q->join('comment_category as category', 'category.comment_id', '=', 'comments_api.sn_id')
        //                 ->where('category.category_id', request()->filter['category']);
        //         }
        //         $q->join('comment_category', 'comment_category.comment_id', '=', 'comments_api.sn_id');
        //     })
        //     ->when(request()->filter && array_key_exists('client_id', request()->filter) && request()->filter['client_id'] !== null, function ($q) {

        //         $q->where('comments_api.sn_client', request()->filter['client_id']);
        //     })
        //     ->when(request()->filter && array_key_exists('service_id', request()->filter) && request()->filter['service_id'] !== null, function ($q) {
        //         $q->where('comments_api.sn_service', request()->filter['service_id']);
        //     })
        //     ->select('comment_topic.topic_id', 'comments_topics.t_name', 'comment_topic.type','comments_api.*')
        //     ->selectRaw("-count(CASE when comment_topic.comment_id = comments_api.sn_id AND comment_topic.type = 'positive' THEN 1 END) AS positive_count")
        //     ->selectRaw("count(CASE when comment_topic.comment_id = comments_api.sn_id AND comment_topic.type = 'negative' THEN 1 END) AS negative_count")
        //     ->where('comments_topics.t_name', request()->topic)
        //     ->where('comment_topic.type', request()->type)
        //     ->groupBy('comment_topic.topic_id', 'comments_api.sn_id')
        //     ->toSql();

            // dd($data);

        return ['data' => $data];
    }
}
