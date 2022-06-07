<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\CommentTopics;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\HelperController;
use App\Http\Filters\CommentTopics\CommentTopicsFilter;

class HomeController extends Controller
{

    public function index()
    {
        // overall statistics
        $overAllComments = $this->getOverAllComments();

        // chuncks  statistics
        $queryChunk = $this->getChunksData();
        $negativeChunks = $queryChunk->negative;
        $positiveChunks = $queryChunk->positive;
        $neutralChunks = $queryChunk->neutral;
        $chunksCount = $negativeChunks + $positiveChunks + $neutralChunks;

        // column-chart
        $catsData = $this->getCategoriesData();

        $categoryChartData = $catsData['categoryChartData'];
        $categories = $catsData['categories'];

        // trend Chart Data
        $trendChartData = $this->getDataMonthly();

        // Topics Data
        $topicsData = $this->getTopicsData();
        $topics = $topicsData['topics'];
        $topicPositive = $topicsData['topicPositive'];
        $topicNegative = $topicsData['topicNegative'];

        // heatmap Data
        $heatmapData = $this->getHeatMapData();
        // data for filters
        $colors = HelperController::getColors();
        $clients = DB::table('clients')->select('c_id', 'c_acronym')->get();
        $services = DB::table('services')->select('s_id', 's_name')->get();
        return view('home', compact('overAllComments', 'chunksCount', 'negativeChunks', 'positiveChunks', 'neutralChunks', 'colors', 'clients', 'services', 'categoryChartData', 'trendChartData', 'topics', 'topicPositive', 'topicNegative', 'categories', 'heatmapData'));
    }

    public function getDataYearly()
    {
        $start_year = Carbon::now()->subYears(3);
        $end_year = Carbon::now();
        $period = collect(CarbonPeriod::create($start_year, '1 year', $end_year))->map(function ($date) {
            return $date->format('Y');
        })->toArray();

        $commentsYearly = HelperController::getCommentApi()->whereBetween('sn_amenddate', [$start_year, $end_year])
            ->select(DB::raw('sn_year as year'), 'r_rate', DB::raw('count(*) as count'))
            ->where('r_rate', '!=', 'mixed')
            ->groupBy('year', 'r_rate')
            ->get();
        $trendChartData = HelperController::trendHandelArr('yearly', $period, $commentsYearly);

        return response()->json($trendChartData);
    }

    public function getDataMonthly()
    {
        $start_date = Carbon::now()->subMonth(11);
        $end_date = Carbon::now();
        $period = collect(CarbonPeriod::create($start_date, '1 month', $end_date))->map(function ($date) {
            return $date->format('Y-m-d');
        })->toArray();
        $commentsMonthly = HelperController::getCommentApi()->whereBetween('sn_amenddate', [$start_date, $end_date])
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

        $commentsQuarterly = HelperController::getCommentApi()->whereBetween('sn_amenddate', [$start_quarter, $end_quarter])
            ->select(DB::raw('sn_year as year'), DB::raw('sn_quarter as quarter'), 'r_rate', DB::raw('count(*) as count'))
            ->where('r_rate', '!=', 'mixed')
            ->orderBy('quarter', 'asc')
            ->groupBy('year', 'quarter', 'r_rate')
            ->get();
        $trendChartData = HelperController::trendHandelArr('quarterly', $period, $commentsQuarterly);
        return response()->json($trendChartData);
    }

    public function getTopicsData()
    {
        $topics = DB::table('comments_topics')
            ->join('comment_topic', 'comment_topic.topic_id', '=', 'comments_topics.t_id')
            ->join('comments_api', 'comments_api.sn_id', '=', 'comment_topic.comment_id')
            ->when(request()->filter && array_key_exists('category',request()->filter), function ($q) {
                $q->join('comment_category', 'comment_category.comment_id', '=', 'comments_api.sn_id')
                    ->where('comment_category.category_id', request()->filter['category']);
            })
            ->when(request()->filter && array_key_exists('client_id', request()->filter) , function ($q) {
                $q->where('comments_api.sn_client', request()->filter['client_id']);
            })
            ->when(request()->filter && array_key_exists('service_id', request()->filter) , function ($q) {
                $q->where('comments_api.sn_service', request()->filter['service_id']);
            })
            ->select('comment_topic.topic_id', 'comments_topics.t_name', 'comment_topic.type')
            ->selectRaw("count(CASE when comment_topic.type = 'positive' THEN 1 END) AS positive_count")
            ->selectRaw("count(CASE when comment_topic.type = 'negative' THEN 1 END) AS negative_count")
            ->groupBy('comment_topic.topic_id', 'comments_topics.t_name')
            ->get();

        $topicPositive = $topics->pluck('positive_count')->toArray();
        $topicNegative = $topics->pluck('negative_count')->toArray();
        return [
            'topics' => $topics,
            'topicPositive' => $topicPositive,
            'topicNegative' => $topicNegative
        ];
    }

    private function getOverAllComments()
    {
        return HelperController::getCommentApi()
            ->selectRaw("count(CASE when r_rate = 'positive' THEN 1 END) AS positive")
            ->selectRaw("count(CASE when r_rate = 'negative' THEN 1 END) AS negative")
            ->selectRaw("count(CASE when r_rate = 'neutral' THEN 1 END) AS neutral")
            ->selectRaw("count(CASE when r_rate = 'mixed' THEN 1 END) AS mixed")
            ->first();
    }

    private function getChunksData()
    {
        return HelperController::getCommentApi()->join('chunks', 'chunks.sn_id', '=', 'comments_api.sn_id')
            ->selectRaw("COUNT(CASE WHEN comments_api.sn_id = chunks.sn_id and chunks.ch_rate = 'positive' THEN 1 END) AS positive")
            ->selectRaw("count(CASE WHEN comments_api.sn_id = chunks.sn_id and chunks.ch_rate = 'negative' THEN 1 END) AS negative")
            ->selectRaw("COUNT(CASE WHEN comments_api.sn_id = chunks.sn_id and chunks.ch_rate = 'neutral' THEN 1 END) AS neutral")
            ->first();
    }

    private function getCategoriesData()
    {
        $categories = HelperController::getCommentCategory()
            ->join('comment_category', 'comment_category.category_id', '=', 'comments_categories.c_id')
            ->join('comments_api', 'comments_api.sn_id', '=', 'comment_category.comment_id')
            ->select('comment_category.category_id', 'comments_categories.c_name as c_name', 'comments_api.sn_id')
            ->selectRaw("count(CASE WHEN comments_api.sn_id = comment_category.comment_id and comments_api.r_rate = 'positive' THEN 1 END) AS positive_count")
            ->selectRaw("count(CASE WHEN comments_api.sn_id = comment_category.comment_id and comments_api.r_rate = 'negative' THEN 1 END) AS negative_count")
            ->selectRaw("count(CASE WHEN comments_api.sn_id = comment_category.comment_id and comments_api.r_rate = 'neutral' THEN 1 END) AS neutral_count")
            ->groupBy('comments_categories.c_id', 'comments_categories.c_name')
            ->get();

        // $categories = HelperController::getCommentCategory()->whereHas('comments')->select()->withCount(['negative', 'positive', 'neutral'])->get();
        $categoryChartData = [
            'positive' => [
                'data' => $categories->pluck('positive_count')->toArray(),
                'name' =>   $categories->pluck('c_name')->toArray()
            ],
            'negative' => [
                'data' => $categories->pluck('negative_count')->toArray(),
                'name' =>   $categories->pluck('c_name')->toArray()
            ],
            'neutral' => [
                'data' => $categories->pluck('neutral_count')->toArray(),
                'name' =>   $categories->pluck('c_name')->toArray()
            ]
        ];
        // dd($categoryChartData);
        return ['categoryChartData' => $categoryChartData, 'categories' => $categories];
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

        $date = [];
        $categories = $categories->map(function ($category) use (&$date) {
            if (array_key_exists($category->category_name, $date)) {
                $date[$category->category_name][$category->topic_name] = $category->count;
            } else {
                $date[$category->category_name] = [$category->topic_name => $category->count];
            }
            return $date;
        });

        return $date;


    }
}
