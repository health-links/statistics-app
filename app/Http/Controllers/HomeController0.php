<?php

namespace App\Http\Controllers;

use App\Http\Filters\CommentApi\CommentApiFilter;
use App\Models\CommentApi;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\Models\CommentTopics;
use App\Models\CommentCategory;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    public function index(Request $request)
    {
        // overall statistics
        $overAllComments = $this->getOverAllComments($request);

        // chuncks  statistics
        $queryChunk = $this->getChunksData($request);
        $negativeChunks = $queryChunk->negative;
        $positiveChunks = $queryChunk->positive;
        $neutralChunks = $queryChunk->neutral;
        $chunksCount = $negativeChunks + $positiveChunks + $neutralChunks;

        // column-chart
        $catsData = $this->getCategoriesData($request);

        $categoryChartData = $catsData['categoryChartData'];
        $categories = $catsData['categories'];
        // dd($categoryChartData);
        // trend Chart Data
        $trendChartData = $this->getDataMonthly($request);

        // Topics Data
        $topicsData = $this->getTopicsData($request);
        $topics = $topicsData['topics'];
        $topicPositive = $topicsData['topicPositive'];
        $topicNegative = $topicsData['topicNegative'];

        // data for filters
        $colors = $this->getColors();
        $clients = DB::table('clients')->select('c_id', 'c_acronym')->get();
        $services = DB::table('services')->select('s_id', 's_name')->get();


        return view('home', compact('overAllComments', 'chunksCount', 'negativeChunks', 'positiveChunks', 'neutralChunks', 'colors', 'clients', 'services', 'categoryChartData', 'trendChartData', 'topics', 'topicPositive', 'topicNegative', 'categories'));
    }



    public function getDataYearly(Request $request)
    {

        $start_year = Carbon::now()->subYears(3);
        $end_year = Carbon::now();
        $period = collect(CarbonPeriod::create($start_year, '1 year', $end_year))->map(function ($date) {
            return $date->format('Y');
        })->toArray();
        // get comments api data and count comments group by r_rate yearly
        $commentsYearly = $this->filterData()->whereBetween('sn_amenddate', [$start_year, $end_year])
            ->select(DB::raw('sn_year as year'), 'r_rate', DB::raw('count(*) as count'))
            ->where('r_rate', '!=', 'mixed')
            ->groupBy('year', 'r_rate')
            ->get();
        $trendChartData = $this->trendHandelArr('yearly', $period, $commentsYearly);

        return response()->json($trendChartData);
    }

    public function getDataMonthly(Request $request)
    {

        $start_date = Carbon::now()->subMonth(11);
        $end_date = Carbon::now();
        $period = collect(CarbonPeriod::create($start_date, '1 month', $end_date))->map(function ($date) {
            return $date->format('Y-m-d');
        })->toArray();
        $commentsMonthly = $this->filterData()->whereBetween('sn_amenddate', [$start_date, $end_date])
            ->select(DB::raw('sn_year as year'), DB::raw('sn_month as month'), 'r_rate', DB::raw('count(*) as count'))
            ->where('r_rate', '!=', 'mixed')
            ->orderBy('month', 'asc')
            ->groupBy('year', 'month', 'r_rate')
            ->get();
        $trendChartData = $this->trendHandelArr('monthly', $period, $commentsMonthly);
        return $trendChartData;
    }

    public function getDataQuarterly(Request $request)
    {
        // get comments api data and count comments group by r_rate quarterly
        $start_quarter = Carbon::now()->subQuarter(5);
        $end_quarter = Carbon::now();
        $period = collect(CarbonPeriod::create($start_quarter, '1 quarter', $end_quarter))->map(function ($date) {
            return $date->format('Y-m-d');
        })->toArray();

        $commentsQuarterly = $this->filterData()->whereBetween('sn_amenddate', [$start_quarter, $end_quarter])
            ->select(DB::raw('sn_year as year'), DB::raw('sn_quarter as quarter'), 'r_rate', DB::raw('count(*) as count'))
            ->where('r_rate', '!=', 'mixed')
            ->orderBy('quarter', 'asc')
            ->groupBy('year', 'quarter', 'r_rate')
            ->get();
        $trendChartData = $this->trendHandelArr('quarterly', $period, $commentsQuarterly);
        return response()->json($trendChartData);
    }

    public function getTopicsData(Request $request)
    {
        $allTopics = CommentTopics::filterData($request);
        $topics = $allTopics->select('t_id', 't_name')->get();
        $ids = $allTopics->pluck('t_id')->toArray();
        $dataPivot = DB::table('comment_topic')->whereIn('topic_id', $ids)->select('topic_id', 'type')->get();
        $topicPositive = [];
        $topicNegative = [];
        $topics->map(function ($topic) use ($dataPivot, &$topicPositive, &$topicNegative) {
            array_push($topicPositive, $dataPivot->where('topic_id', $topic->t_id)->where('type', 'positive')->count());
            array_push($topicNegative, -$dataPivot->where('topic_id', $topic->t_id)->where('type', 'negative')->count());
        });
        return [
            'topics' => $topics,
            'topicPositive' => $topicPositive,
            'topicNegative' => $topicNegative
        ];
    }

    private function getColors()
    {
        $data = json_decode(DB::table('charts_bgs')->where('bkey', 'rates')->first()->bvals);
        return  $data;
    }
    private function filterData()
    {
        return (new CommentApiFilter());
    }


    private function getOverAllComments($request)
    {
         return $this->filterData()
            ->selectRaw("count(CASE when r_rate = 'positive' THEN 1 END) AS positive")
            ->selectRaw("count(CASE when r_rate = 'negative' THEN 1 END) AS negative")
            ->selectRaw("count(CASE when r_rate = 'neutral' THEN 1 END) AS neutral")
            ->selectRaw("count(CASE when r_rate = 'mixed' THEN 1 END) AS mixed")
            ->first();
    }

    private function getChunksData()
    {
        return $this->filterData()->join('chunks', 'chunks.sn_id', '=', 'comments_api.sn_id')
        ->selectRaw("COUNT(CASE WHEN comments_api.sn_id = chunks.sn_id and chunks.ch_rate = 'positive' THEN 1 END) AS positive")
        ->selectRaw("count(CASE WHEN comments_api.sn_id = chunks.sn_id and chunks.ch_rate = 'negative' THEN 1 END) AS negative")
        ->selectRaw("COUNT(CASE WHEN comments_api.sn_id = chunks.sn_id and chunks.ch_rate = 'neutral' THEN 1 END) AS neutral")
        ->first();
    }

    private function getCategoriesData($request)
    {
        $categories = CommentCategory::filterData($request)->select('c_id', 'c_name')->get();
        $CatsData = [];
        $types = ['positive', 'negative', 'neutral'];

        $categories->map(function ($category) use ($types, &$CatsData) {
           collect($types)->map(function ($type) use ($category, &$CatsData) {
                $CatsData[] = [
                    'id' => $category->c_id,
                    'name' => $category->c_name,
                    'type' =>  $type,
                    'value' => $category->comments->where('r_rate', '=', $type)->count()
                ];
                return $CatsData;
           });
        });
        $categoryChartData = [];
        collect($CatsData)->map(function ($item) use (&$categoryChartData) {
            $categoryChartData[$item['type']]['data'][] = $item['value'];
            $categoryChartData[$item['type']]['name'][] = $item['name'];
        });
        return ['categoryChartData'=> $categoryChartData, 'categories'=>$categories];
    }


    private function trendHandelArr($type = 'montly', $period, $data)
    {

        $chartColor = collect($this->getColors())->toArray();
        $trendChartData = [];
        foreach ($period as $item) {
            if ($type == 'monthly') {
                $m = Carbon::parse($item)->month;
                $y = Carbon::parse($item)->year;
                $filterData = $data->where('month', '=', $m)->where('year', $y);
            } elseif ($type == 'quarterly') {
                $q = Carbon::parse($item)->quarter;
                $y = Carbon::parse($item)->year;
                $filterData = $data->where('quarter', '=', $q)->where('year', $y);
            } else {

                $filterData = $data->where('year', $item);
            }

            if (count($filterData) > 0) {
                foreach ($filterData as $value) {
                    if ($type == 'monthly') {
                        $cat = date('M', mktime(0, 0, 0, $value->month, 10)) . ' ' . substr($value->year, -2);
                    } elseif ($type == 'quarterly') {
                        $cat = 'Q' . $value->quarter . '-' . substr($value->year, -2);
                    } else {
                        $cat = $value->year;
                    }
                    $rate = $value->r_rate;
                    $trendChartData[$rate]['color'] =  'rgb' . $chartColor[$rate];
                    $trendChartData[$rate]['data'][] =  $value->count;
                    $trendChartData[$rate]['categories'][] =  $cat;
                }
            } else {
                if ($type == 'monthly') {
                    $cat = date('M', mktime(0, 0, 0, $m, 10)) . ' ' . substr($item, -2);
                } elseif ($type == 'quarterly') {
                    $cat = 'Q' . $q . '-' . substr($y, -2);
                } else {
                    $cat = $item;
                }
                $trendChartData['neutral']['color'] =  'rgb' . $chartColor['neutral'];
                $trendChartData['neutral']['data'][] = 0;
                $trendChartData['neutral']['categories'][] =   $cat;

                $trendChartData['positive']['color'] =  'rgb' . $chartColor['positive'];
                $trendChartData['positive']['data'][] = 0;
                $trendChartData['positive']['categories'][] = $cat;

                $trendChartData['negative']['color'] =  'rgb' . $chartColor['negative'];
                $trendChartData['negative']['data'][] = 0;
                $trendChartData['negative']['categories'][] = $cat;
            }
        }
        return $trendChartData;
    }
}
