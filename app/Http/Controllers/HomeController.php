<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Services\CommentApiService;
use App\Http\Controllers\HelperController;
use App\Services\CommentTopicService;
use App\Services\CommentCategoryService;


class HomeController extends Controller
{
    private $topicService, $commentService, $commentCategoryService;
    public function __construct(
        CommentTopicService $topicService,
        CommentApiService $commentService,
        CommentCategoryService $commentCategoryService
    ) {
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

        $topTopics = $this->topicService->getTopTopics();
        // dd($topTopics);
        // heatmap Data
        $heatmapData = $this->getHeatMapData();

        // data for filters
        $colors = HelperController::getColors();
        $clients = DB::table('clients')->select('c_id', 'c_acronym')->get();
        $services = DB::table('services')->select('s_id', 's_name')->get();
        return view('home', compact('overAllComments', 'chunksCount', 'negativeChunks', 'positiveChunks', 'neutralChunks', 'colors', 'clients', 'services', 'categoryChartData', 'trendChartData', 'topics', 'topicPositive', 'topicNegative', 'categories', 'heatmapData','topTopics'));
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
        return $categoryChartData;
    }

    private function getHeatMapData()
    {
        // $categories = $this->commentCategoryService->getHeatMapData();
        // dd($categories);
        // $date = [];
        // $categoriesTopics = $categories->pluck('topic_name')->unique();
        // $categoriesTopics->map(function ($topic) use (&$date, $categories) {
        //     $categories->map(function ($category) use (&$date, $topic) {
        //         if (array_key_exists($category->category_name, $date)) {
        //             if ($category->topic_name == $topic) {
        //                 $date[$category->category_name][$topic] = $category->count;
        //             } elseif (!array_key_exists($topic, $date[$category->category_name])) {
        //                 $date[$category->category_name][$topic] = 0;
        //             }
        //         } else {
        //             if ($category->topic_name == $topic) {
        //                 $date[$category->category_name] = [$topic => $category->count];
        //             } else {
        //                 $date[$category->category_name] = [$topic => 0];
        //             }
        //         }
        //     });
        //     return $date;
        // });
        $topics = $this->commentCategoryService->getHeatMapData();
        $date = [];
        $categoriesTopics = $topics->pluck('category_name')->unique();
        $categoriesTopics->map(function ($category) use (&$date, $topics) {
            $topics->map(function ($topic) use (&$date, $category) {

                if (array_key_exists($topic->topic_name, $date)) {
                    if ($topic->category_name == $category) {
                        $date[$topic->topic_name][$category] = $topic->count;
                    } elseif (!array_key_exists($category, $date[$topic->topic_name])) {
                        $date[$topic->topic_name][$category] = 0;
                    }
                } else {
                    if ($topic->category_name == $category) {
                        $date[$topic->topic_name] = [$category => $topic->count];
                    } else {
                        $date[$topic->topic_name] = [$category => 0];
                    }
                }
            });
            return $date;
        });


        return $date;
    }


    // tables when click on chart

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

    public function getCommentsTrend()
    {
        return $this->commentService->getCommentsTrend();
    }
    public function getCommentsCategory()
    {
        return $this->commentService->getCommentsCategory();
    }
    public function getCommentsTopic()
    {
        return $this->topicService->getCommentsTopic();
    }

    public function getHeatMapComments()
    {
        return $this->commentCategoryService->getHeatMapComments();
    }
}
