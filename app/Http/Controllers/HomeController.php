<?php

namespace App\Http\Controllers;

use App\Models\Chunk;
use App\Models\CommentApi;
use App\Models\CommentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        // get all categories from comments_categories


        // select r_cats from comments_api

        // $data = DB::table('comments_api')
        //         ->select('comments_categories.*', DB::raw("services.name as name"))
        //         ->leftJoin('comments_api as comments', DB::raw("FIND_IN_SET(c_id, comments.r_cats)"), '>', DB::raw("'0'"))
        //         ->get();


        // $data = DB::table("comments_api")
        //     ->whereNotNull('r_cats')
        //     ->select("comments_api.*", DB::raw("GROUP_CONCAT(comments_categories.c_name ORDER BY comments_api.sn_id) cat_name"))
        //     ->leftjoin("comments_categories", DB::raw('FIND_IN_SET(comments_categories.c_id, comments_api.r_cats)'), '>', DB::raw("'0'"))
        //     ->GroupBy( 'comments_api.r_cats')
        //     ->get();

        // $location = CommentApi::whereNotNull('r_cats')->leftJoin('comments_categories', function ($join) {
        //     $join->on("comments_categories", DB::raw('FIND_IN_SET(comments_categories.c_id, comments_api.r_cats)'), '>', DB::raw("'0'"));
        // })
        // ->select("comments_api.*", DB::raw("GROUP_CONCAT(comments_categories.c_name ORDER BY comments_api.sn_id) cat_name"))
        // ->GroupBy('location.id')
        // ->paginate(10);
        // $data = CommentApi::join('comments_categories', DB::raw("FIND_IN_SET(c_id, comment_api.r_cats)"), '>', DB::raw("'0'"))->get();
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
        foreach($rr as $key => $value) {
             $rrrrrr[$value['type']]['data'][] = $value['value'];
            $rrrrrr[$value['type']]['name'][] = $value['name'];
        }

        // dd($rrrrrr);

        //
        /**
         * $data=[
         * 'name'=>'',
         * 'type'=>'',
         * 'data'=>[],
         * ]
         */


        // manay to many relation without pivot table



        return view('home', compact('positive', 'negative', 'neutral', 'mixed', 'chunks', 'negativeChunks', 'positiveChunks', 'neutralChunks', 'mixedChunks', 'colors', 'clients', 'services', 'rrrrrr'));
    }

    public function getdd()
    {
        // join between to tables comments_categories and comments_api

        $comments = CommentApi::join('comments_categories', 'comments_api.sn_comment', '=', 'comments_categories.sn_comment')
            ->select('comments_api.*', 'comments_categories.category_id')
            ->get();
    }
}
