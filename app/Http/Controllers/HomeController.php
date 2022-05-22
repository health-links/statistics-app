<?php

namespace App\Http\Controllers;

use App\Models\Chunk;
use App\Models\CommentApi;
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
        $negativeChunks = $chunks->where('ch_rate', '=', 'negative')->first();
        $positiveChunks = $chunks->where('ch_rate', '=', 'positive')->first();
        $neutralChunks = $chunks->where('ch_rate', '=', 'neutral')->first();
        $mixedChunks = $chunks->where('ch_rate', '=', 'mixed')->first();
        // get colors from charts_bgs
        $colors = json_decode(DB::table('charts_bgs')->where('bkey', 'rates')->first()->bvals);
        $clients = DB::table('clients')->get();
        $services = DB::table('services')->get();

        return view('home', compact('positive', 'negative', 'neutral', 'mixed', 'chunks', 'negativeChunks', 'positiveChunks', 'neutralChunks', 'mixedChunks', 'colors', 'clients', 'services'));
    }
}
