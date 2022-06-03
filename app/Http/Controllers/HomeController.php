<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Chunk;
use App\Models\CommentApi;
use Illuminate\Http\Request;
use App\Models\CommentCategory;
use App\Models\CommentTopics;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    public function index(Request $request)
    {
        // overall statistics
        $overAllComments = $this->filterData($request)
                                        ->selectRaw("count(CASE when r_rate = 'positive' THEN 1 END) AS positive")
                                        ->selectRaw("count(CASE when r_rate = 'negative' THEN 1 END) AS negative")
                                        ->selectRaw("count(CASE when r_rate = 'neutral' THEN 1 END) AS neutral")
                                        ->selectRaw("count(CASE when r_rate = 'mixed' THEN 1 END) AS mixed")
                                        ->first();
        // chuncks  statistics
        $queryChunk = $this->filterData($request)->join('chunks', 'chunks.sn_id', '=', 'comments_api.sn_id')
                                        ->selectRaw("COUNT(CASE WHEN comments_api.sn_id = chunks.sn_id and chunks.ch_rate = 'positive' THEN 1 END) AS positive")
                                        ->selectRaw("count(CASE WHEN comments_api.sn_id = chunks.sn_id and chunks.ch_rate = 'negative' THEN 1 END) AS negative")
                                        ->selectRaw("COUNT(CASE WHEN comments_api.sn_id = chunks.sn_id and chunks.ch_rate = 'neutral' THEN 1 END) AS neutral")
                                        ->selectRaw("COUNT(CASE WHEN comments_api.sn_id = chunks.sn_id and chunks.ch_rate = 'mixed' THEN 1 END) AS mixed")
                                        ->first();

        $negativeChunks = $queryChunk->negative;
        $positiveChunks = $queryChunk->positive;
        $neutralChunks = $queryChunk->neutral;
        $mixedChunks = $queryChunk->mixed;
        $chunksCount = $negativeChunks + $positiveChunks + $neutralChunks+ $mixedChunks;


        // column-chart
        $categories = CommentCategory::filterData($request)->select('c_id', 'c_name')->get();
        $chunksData = [];
        $types = ['positive', 'negative', 'neutral', 'mixed'];
        foreach ($categories as $key => $value) {
            foreach ($types as $type) {
                $chunksData[] = [
                    'id' => $value->c_id,
                    'name' => $value->c_name,
                    'type' =>  $type,
                    'value' => $value->comments->where('r_rate', '=', $type)->count()
                ];
            }
        }
        $chunksChartData = [];
        foreach (collect($chunksData)->toArray() as $key => $value) {
            $chunksChartData[$value['type']]['data'][] = $value['value'];
            $chunksChartData[$value['type']]['name'][] = $value['name'];
        }


        // get comments api data and count comments group by r_rate monthly
        $trendChartData = $this->getDataMonthly($request);

        // Topics Data
        $topicsData = $this->getTopicsData($request);
        $topics = $topicsData['topics'];
        $topicPositive = $topicsData['topicPositive'];
        $topicNegative = $topicsData['topicNegative'];


        $colors = $this->getColors();
        $clients = DB::table('clients')->select('c_id', 'c_acronym')->get();
        $services = DB::table('services')->select('s_id', 's_name')->get();

        return view('home', compact('overAllComments', 'chunksCount', 'negativeChunks', 'positiveChunks', 'neutralChunks', 'mixedChunks', 'colors', 'clients', 'services', 'chunksChartData', 'trendChartData', 'topics', 'topicPositive', 'topicNegative', 'categories'));
    }



    public function getDataYearly(Request $request)
    {
        // get comments api data and count comments group by r_rate yearly
        $commentsYearly = $this->filterData($request)->whereBetween('sn_amenddate', [Carbon::now()->subYear(1), Carbon::now()])
            ->select(DB::raw('YEAR(sn_amenddate) as year'), 'r_rate', DB::raw('count(*) as count'))
            ->groupBy('year', 'r_rate')
            ->get();

        $chartColor = collect($this->getColors())->toArray();
        foreach ($commentsYearly as $key => $value) {
            $rate = $value->r_rate;
            $trendChartData[$rate]['color'] =  'rgb' . $chartColor[$rate];
            $trendChartData[$rate]['data'][] = $value->count;
            $trendChartData[$rate]['categories'][] = $value->year;
        }
        return response()->json($trendChartData);
    }

    public function getDataMonthly(Request $request)
    {

        $commentsMonthly = $this->filterData($request)->whereBetween('sn_amenddate', [Carbon::now()->subMonth(12), Carbon::now()])
            ->select(DB::raw('YEAR(sn_amenddate) as year'), DB::raw('MONTH(sn_amenddate) as month'), 'r_rate', DB::raw('count(*) as count'))
            ->orderBy('month', 'asc')
            ->groupBy('year', 'month', 'r_rate')
            ->get();

        $chartColor = collect($this->getColors())->toArray();
        $trendChartData = [];
        foreach ($commentsMonthly as $key => $value) {
            $rate = $value->r_rate;
            $trendChartData[$rate]['color'] =  'rgb' . $chartColor[$rate];
            $trendChartData[$rate]['data'][] = $value->count;
            $trendChartData[$rate]['categories'][] = $value->year . '-' . date('F', mktime(0, 0, 0, $value->month, 10));
        }
        return $trendChartData;
    }

    public function getDataQuarterly(Request $request)
    {
        // get comments api data and count comments group by r_rate quarterly
        $commentsQuarterly = $this->filterData($request)->whereBetween('sn_amenddate', [Carbon::now()->subMonth(3), Carbon::now()])
            ->select(DB::raw('YEAR(sn_amenddate) as year'), DB::raw('QUARTER(sn_amenddate) as quarter'), 'r_rate', DB::raw('count(*) as count'))
            ->orderBy('quarter', 'asc')
            ->groupBy('year', 'quarter', 'r_rate')
            ->get();

        $chartColor = collect($this->getColors())->toArray();
        $trendChartData = [];
        foreach ($commentsQuarterly as $key => $value) {
            $rate = $value->r_rate;
            $trendChartData[$rate]['color'] =  'rgb' . $chartColor[$rate];
            $trendChartData[$rate]['data'][] = $value->count;
            $trendChartData[$rate]['categories'][] = $value->year . '-' . $value->quarter;
        }
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
        foreach ($topics as $key => $value) {
            array_push($topicPositive, $dataPivot->where('topic_id', $value->t_id)->where('type', 'positive')->count());
            array_push($topicNegative, -$dataPivot->where('topic_id', $value->t_id)->where('type', 'negative')->count());
        }
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
    private function filterData($request)
    {
        return CommentApi::filterData($request);
    }
}
