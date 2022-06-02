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
        $queryComment = $this->filterData($request);
        $comments = $queryComment->select('r_rate', DB::raw('count(*) as count'))
            ->groupBy('r_rate')
            ->get();
        // get postive comments from $comments
        $negative = $comments->where('r_rate', '=', 'negative')->first();
        $positive = $comments->where('r_rate', '=', 'positive')->first();
        $neutral = $comments->where('r_rate', '=', 'neutral')->first();
        $mixed = $comments->where('r_rate', '=', 'mixed')->first();

        // chunks
        // get all chuncks grouped by r_rate
        $queryChunk = Chunk::query();
        $queryChunk->when($request->service_id !== null, function ($q) use ($request) {
            return $q->where('ch_service', $request->service_id);
        });
        $chunks = $queryChunk->select('ch_rate', DB::raw('count(*) as count'))
            ->groupBy('ch_rate')
            ->get();
        // get postive chunks from $chunks
        $negativeChunks = $chunks->where('ch_rate', '=', 'negative')->count();
        $positiveChunks = $chunks->where('ch_rate', '=', 'positive')->count();
        $neutralChunks = $chunks->where('ch_rate', '=', 'neutral')->count();
        $mixedChunks = $chunks->where('ch_rate', '=', 'mixed')->count();
        // get colors from charts_bgs
        $colors = json_decode(DB::table('charts_bgs')->where('bkey', 'rates')->first()->bvals);
        $clients = DB::table('clients')->get();
        $services = DB::table('services')->get();

        // column-chart
        $categories = CommentCategory::whereHas('comments')->where('c_report','=',1)->get();
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
        $topics = $this->getTopicsData($request)['topics'];
        $topicPositive = $this->getTopicsData($request)['topicPositive'];
        $topicNegative = $this->getTopicsData($request)['topicNegative'];

        return view('home', compact('positive', 'negative', 'neutral', 'mixed', 'chunks', 'negativeChunks', 'positiveChunks', 'neutralChunks', 'mixedChunks', 'colors', 'clients', 'services', 'chunksChartData', 'trendChartData', 'topics', 'topicPositive', 'topicNegative', 'categories'));
    }



    public function getDataYearly(Request $request)
    {
        // get comments api data and count comments group by r_rate yearly
        $queryCommentYearly = $this->filterData($request);
        $commentsYearly = $queryCommentYearly->whereBetween('sn_amenddate', [Carbon::now()->subYear(1), Carbon::now()])
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
        $queryCommentMonthly = $this->filterData($request);

        $commentsMonthly = $queryCommentMonthly->whereBetween('sn_amenddate', [Carbon::now()->subMonth(12), Carbon::now()])
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
        $queryCommentQuarterly = $this->filterData($request);
        $commentsQuarterly = $queryCommentQuarterly->whereBetween('sn_amenddate', [Carbon::now()->subMonth(3), Carbon::now()])
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
        // using when with realtionship
        $query = CommentTopics::where('t_report', '=', 1);
        $query->when($request->category !== null && $request->category !== 'all', function($q){
             $q->whereHas('comments',function($q2){
                 $q2->whereHas('categories',function($q3){
                    return $q3->where('c_id',request()->category)->where('c_report','=',1);
                });
            });
        });
        $query->when($request->category !== null && $request->category === 'all', function ($q) {
            return $q->whereHas('comments');
        });
        $topics = $query->get();
        $topicPositive = [];
        $topicNegative = [];
        foreach ($topics as $key => $value) {
            array_push($topicPositive, $value->getPositiveTopics());
            array_push($topicNegative, -$value->getNegativeTopics());

        }
        return [
            'topics'=> $topics,
            'topicPositive'=> $topicPositive,
            'topicNegative'=> $topicNegative
        ];
    }

    private function getColors()
    {
        $data = json_decode(DB::table('charts_bgs')->where('bkey', 'rates')->first()->bvals);
        return $data;
    }


    private function filterData($request)
    {
        return CommentApi::filterData($request);
    }
}
