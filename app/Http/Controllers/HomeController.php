<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Chunk;
use Carbon\CarbonPeriod;
use App\Models\CommentApi;
use Illuminate\Http\Request;
use App\Models\CommentTopics;
use App\Models\CommentCategory;
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
            ->first();

        $negativeChunks = $queryChunk->negative;
        $positiveChunks = $queryChunk->positive;
        $neutralChunks = $queryChunk->neutral;
        $chunksCount = $negativeChunks + $positiveChunks + $neutralChunks;



        // column-chart
        $categories = CommentCategory::filterData($request)->select('c_id', 'c_name')->get();
        $CatsData = [];
        $types = ['positive', 'negative', 'neutral'];
        foreach ($categories as $key => $value) {
            foreach ($types as $type) {
                $CatsData[] = [
                    'id' => $value->c_id,
                    'name' => $value->c_name,
                    'type' =>  $type,
                    'value' => $value->comments->where('r_rate', '=', $type)->count()
                ];
            }
        }
        $chunksChartData = [];
        foreach (collect($CatsData)->toArray() as $key => $value) {
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

        return view('home', compact('overAllComments', 'chunksCount', 'negativeChunks', 'positiveChunks', 'neutralChunks', 'colors', 'clients', 'services', 'chunksChartData', 'trendChartData', 'topics', 'topicPositive', 'topicNegative', 'categories'));
    }



    public function getDataYearly(Request $request)
    {

        $start_year = Carbon::now()->subYears(3);
        $end_year = Carbon::now();
        $period = collect(CarbonPeriod::create($start_year, '1 year', $end_year))->map(function ($date) {
            return $date->format('Y');
        })->toArray();


        // get comments api data and count comments group by r_rate yearly
        $commentsYearly = $this->filterData($request)->whereBetween('sn_amenddate', [$start_year, $end_year])
            ->select(DB::raw('sn_year as year'), 'r_rate', DB::raw('count(*) as count'))
            ->where('r_rate', '!=', 'mixed')
            ->groupBy('year', 'r_rate')
            ->get();
        $chartColor = collect($this->getColors())->toArray();
        // foreach $period with $commentsYearly
        $trendChartDataYearly = [];
        foreach ($period as $year) {
            $yearlyData = $commentsYearly->where('year', $year);
            if (count($yearlyData) > 0) {
                foreach ($yearlyData as $value) {
                    $rate = $value->r_rate;
                    $trendChartDataYearly[$rate]['color'] =  'rgb' . $chartColor[$rate];
                    $trendChartDataYearly[$rate]['data'][] =  $value->count;
                    $trendChartDataYearly[$rate]['categories'][] =   $value->year;
                }
            } else {

                $trendChartDataYearly['neutral']['color'] =  'rgb' . $chartColor['neutral'];
                $trendChartDataYearly['neutral']['data'][] = 0;
                $trendChartDataYearly['neutral']['categories'][] =  $year;

                $trendChartDataYearly['positive']['color'] =  'rgb' . $chartColor['positive'];
                $trendChartDataYearly['positive']['data'][] = 0;
                $trendChartDataYearly['positive']['categories'][] =$year;

                $trendChartDataYearly['negative']['color'] =  'rgb' . $chartColor['negative'];
                $trendChartDataYearly['negative']['data'][] = 0;
                $trendChartDataYearly['negative']['categories'][] =$year;
            }
        }

        return response()->json($trendChartDataYearly);
    }

    public function getDataMonthly(Request $request)
    {

        $start_date = Carbon::now()->subMonth(11);
        $end_date = Carbon::now();
        $months = collect(CarbonPeriod::create($start_date, '1 month', $end_date))->map(function ($date) {
            return $date->format('Y-m-d');
        })->toArray();
        $commentsMonthly = $this->filterData($request)->whereBetween('sn_amenddate', [$start_date, $end_date])
            ->select(DB::raw('sn_year as year'), DB::raw('sn_month as month'), 'r_rate', DB::raw('count(*) as count'))
            ->where('r_rate', '!=', 'mixed')
            ->orderBy('month', 'asc')
            ->groupBy('year', 'month', 'r_rate')
            ->get();
        $chartColor = collect($this->getColors())->toArray();

        $trendChartData = [];
        foreach ($months as $month) {

            $m = Carbon::parse($month)->month;
            $y = Carbon::parse($month)->year;
            $monthData = $commentsMonthly->where('month', '=', $m)->where('year', $y);
            if (count($monthData) > 0) {
                foreach ($monthData as $value) {
                    $rate = $value->r_rate;
                    $trendChartData[$rate]['color'] =  'rgb' . $chartColor[$rate];
                    $trendChartData[$rate]['data'][] =  $value->count;
                    $trendChartData[$rate]['categories'][] =  date('M', mktime(0, 0, 0, $value->month, 10)) . ' ' . substr($value->year, -2);
                }
            } else {
                $trendChartData['neutral']['color'] =  'rgb' . $chartColor['neutral'];
                $trendChartData['neutral']['data'][] = 0;
                $trendChartData['neutral']['categories'][] =   date('M', mktime(0, 0, 0, $m, 10)) . ' ' .substr($y, -2) ;

                $trendChartData['positive']['color'] =  'rgb' . $chartColor['positive'];
                $trendChartData['positive']['data'][] = 0;
                $trendChartData['positive']['categories'][] = date('M', mktime(0, 0, 0, $m, 10)) . ' ' . substr($y, -2);

                $trendChartData['negative']['color'] =  'rgb' . $chartColor['negative'];
                $trendChartData['negative']['data'][] = 0;
                $trendChartData['negative']['categories'][] = date('M', mktime(0, 0, 0, $m, 10)) . ' ' . substr($y, -2);
            }
        }


        return $trendChartData;
    }

    public function getDataQuarterly(Request $request)
    {
        // get comments api data and count comments group by r_rate quarterly
        $start_quarter = Carbon::now()->subQuarter(5);
        $end_quarter = Carbon::now();
        $quarters = collect(CarbonPeriod::create($start_quarter, '1 quarter', $end_quarter))->map(function ($date) {
            return $date->format('Y-m-d');
        })->toArray();

        $commentsQuarterly = $this->filterData($request)->whereBetween('sn_amenddate', [$start_quarter, $end_quarter])
            ->select(DB::raw('sn_year as year'), DB::raw('sn_quarter as quarter'), 'r_rate', DB::raw('count(*) as count'))
            ->where('r_rate', '!=', 'mixed')
            ->orderBy('quarter', 'asc')
            ->groupBy('year', 'quarter', 'r_rate')
            ->get();

            // dd($commentsQuarterly);

        $chartColor = collect($this->getColors())->toArray();

        $trendChartData = [];
        foreach ($quarters as $quarter) {
            $q = Carbon::parse($quarter)->quarter;
            $y = Carbon::parse($quarter)->year;
            $quarterData = $commentsQuarterly->where('quarter', $q)->where('year', $y);
            if (count($quarterData) > 0) {
                foreach ($quarterData as $value) {
                    $rate = $value->r_rate;
                    $trendChartData[$rate]['color'] =  'rgb' . $chartColor[$rate];
                    $trendChartData[$rate]['data'][] =  $value->count;
                    $trendChartData[$rate]['categories'][] = 'Q' . $value->quarter . '-' . substr($value->year, -2);
                }
            } else {
                $trendChartData['positive']['color'] =  'rgb' . $chartColor['positive'];
                $trendChartData['positive']['data'][] = 0;
                $trendChartData['positive']['categories'][] = 'Q' . $q . '-' . substr($y, -2);

                $trendChartData['neutral']['color'] =  'rgb' . $chartColor['neutral'];
                $trendChartData['neutral']['data'][] = 0;
                $trendChartData['neutral']['categories'][] = 'Q' . $q . '-' . substr($y, -2);


                $trendChartData['negative']['color'] =  'rgb' . $chartColor['negative'];
                $trendChartData['negative']['data'][] = 0;
                $trendChartData['negative']['categories'][] = 'Q' . $q . '-' . substr($y, -2);


            }
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
