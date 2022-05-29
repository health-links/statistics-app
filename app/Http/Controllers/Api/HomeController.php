<?php

namespace App\Http\Controllers\Api;

use App\Models\Chunk;
use App\Models\CommentApi;
use Illuminate\Http\Request;
use App\Models\CommentCategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // get comments api data and count comments group by r_rate
        $queryComment = CommentApi::query();
        $queryComment->when($request->client_id !== null, function ($q) use ($request) {
            return $q->where('sn_client', $request->client_id);
        });
        $queryComment->when($request->service_id !== null, function ($q) use ($request) {
            return $q->where('sn_service', $request->service_id);
        });
        $queryComment->when($request->duration !== null, function ($q) use ($request) {
            return $q->where('sn_amenddate', '<', date('Y-m-d', strtotime('-' . $request->duration . ' days')));
        });

        $comments = $queryComment->select('r_rate', DB::raw('count(*) as count'))
            ->groupBy('r_rate')
            ->get();
        // get postive comments from $comments
        $negative = $comments->where('r_rate', '=', 'negative')->first();
        $positive = $comments->where('r_rate', '=', 'positive')->first();
        $neutral = $comments->where('r_rate', '=', 'neutral')->first();
        $mixed = $comments->where('r_rate', '=', 'mixed')->first();

        $sataData = [
            [
                "icon" => 'TrendingUpIcon',
                "color" => 'light-primary',
                "title" => $negative->count,
                "subtitle" => 'negative',
                "customClass" => 'mb-2 mb-xl-0',
            ],
            [
                "icon" => 'UserIcon',
                "color" => 'light-info',
                "title" => $positive->count,
                "subtitle" => 'positive',
                "customClass" => 'mb-2 mb-xl-0',
            ],
            [
                "icon" => 'BoxIcon',
                "color" => 'light-danger',
                "title" => $neutral->count,
                "subtitle" => 'neutral',
                "customClass" => 'mb-2 mb-xl-0',
            ],
            [
                "icon" => 'DollarSignIcon',
                "color" => 'light-success',
                "title" => $mixed->count,
                "subtitle" => 'mixed',
                "customClass" => '',
            ],
        ];

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

        $dount =[
            'series'=>[$negativeChunks, $positiveChunks, $neutralChunks, $mixedChunks],
            'labels'=>['negative', 'positive', 'neutral', 'mixed'],
            'colors' => $colors,
            'total' => $chunks->count(),
        ];
        $clients = DB::table('clients')->get();
        $services = DB::table('services')->get();

        $data = CommentCategory::whereHas('comments', function ($query) {
            $query->whereNotNull('r_cats');
        })->get();
        $datas = [];
        $types = ['positive', 'negative', 'neutral', 'mixed'];
        foreach ($data as $key => $value) {
            foreach ($types as $type) {
                $datas[] = [
                    'id' => $value->c_id,
                    'name' => $value->c_name,
                    'type' =>  $type,
                    'value' => $value->comments->where('r_rate', '=', $type)->count()
                ];
            }
        }

        $rr = collect($datas)->toArray();

        $rrrrrr = [];
        foreach ($rr as $key => $value) {
            $rrrrrr[$value['type']]['data'][] = $value['value'];
            $rrrrrr[$value['type']]['name'][] = $value['name'];
        }


        // manay to many relation without pivot table

        return response()->json([
            'sataData' => $sataData,
            'dount' => $dount,
            'clients' => $clients,
            'services' => $services,
            'colors' => $colors,
            'data' => $rrrrrr
        ],200);
    }

    public function getdd()
    {
        // join between to tables comments_categories and comments_api

        $comments = CommentApi::join('comments_categories', 'comments_api.sn_comment', '=', 'comments_categories.sn_comment')
            ->select('comments_api.*', 'comments_categories.category_id')
            ->get();
    }
}
