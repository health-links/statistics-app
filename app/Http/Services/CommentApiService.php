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
    public function getCommentsTypes()
    {
        $data = $this->getCommentApi()
            ->where('r_rate', request()->type)
            ->with(['topics', 'categories', 'client'])
            ->latest('sn_amenddate')
            ->paginate(100);
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
    public function getCommentsChunks()
    {
        $data = $this->getCommentApi()
            ->join('chunks', 'chunks.sn_id', '=', 'comments_api.sn_id')
            ->select('comments_api.*', 'chunks.ch_id', 'chunks.ch_rate')
            ->where('chunks.ch_rate', request()->type)
            ->groupBy('chunks.ch_rate', 'chunks.ch_id')
            ->get();
        return ['data'=>$data];
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
            ->groupBy('year', 'quarter', 'r_rate')
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
        return response()->json(["status"=>'success','message'=>'Comment flagged successfully']);
    }
    public function updateBookmark()
    {
        $comment = $this->getCommentApi()->findOrFail(request()->id);
        $comment->bookmarked = !$comment->bookmarked;
        $comment->save();
        return response()->json(["status" => 'success','message'=>'Bookmark updated']);
    }
}
