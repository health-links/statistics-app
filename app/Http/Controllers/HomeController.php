<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Services\CommentApiService;
use App\Http\Controllers\HelperController;
use App\Http\Services\CommentTopicService;
use App\Http\Services\CommentCategoryService;


class HomeController extends Controller
{
    private $topicService, $commentService, $commentCategoryService;
    public function __construct(CommentTopicService $topicService,
    CommentApiService $commentService,
    CommentCategoryService $commentCategoryService)
    {
        $this->topicService = $topicService;
        $this->commentService = $commentService;
        $this->commentCategoryService = $commentCategoryService;
    }

    public function index()
    {
        // overall statistics
        $overAllComments = $this->commentService->getOverAllComments();
        // chuncks  statistics
        $queryChunk = $this->commentService->getChunksData();
        $negativeChunks = $queryChunk->negative;
        $positiveChunks = $queryChunk->positive;
        $neutralChunks = $queryChunk->neutral;
        $chunksCount = $negativeChunks + $positiveChunks + $neutralChunks;

        // column-chart
        $categoryChartData = $this->getCategoriesData();
        $categories = $this->commentCategoryService->getCategories();

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
        $trendChartData = $this->commentService->getDataYearly();
        return response()->json($trendChartData);
    }

    public function getDataMonthly()
    {
        $trendChartData = $this->commentService->getDataMonthly();
        return $trendChartData;
    }

    public function getDataQuarterly()
    {
        $trendChartData = $this->commentService->getDataQuarterly();
        return $trendChartData;
    }

    public function getTopicsData()
    {
        $topics = $this->topicService->getTopicsData();
        $topicPositive = $topics->pluck('positive_count')->toArray();
        $topicNegative = $topics->pluck('negative_count')->toArray();
        return [
            'topics' => $topics,
            'topicPositive' => $topicPositive,
            'topicNegative' => $topicNegative
        ];
    }

    private function getCategoriesData()
    {

        $categories = $this->commentCategoryService->getCategoriesData();
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
        return $categoryChartData;
    }

    private function getHeatMapData()
    {
        $categories = $this->commentCategoryService->getHeatMapData();
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

    public function getComments()
    {

        return $this->commentService->getComments();
    }
    public function getCommentsTypes()
    {
        return $this->commentService->getCommentsTypes();
    }

    public function getCommentsChunks()
    {
        return $this->commentService->getCommentsChunks();
    }
}
