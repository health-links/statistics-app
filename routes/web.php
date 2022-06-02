<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



// home route
Route::get('/', [HomeController::class,'index'])->name('home');
Route::get('/yearly', [HomeController::class, 'getDataYearly'])->name('chart.yearly');
Route::get('/monthly', [HomeController::class, 'getDataMonthly'])->name('chart.monthly');
Route::get('/quarterly', [HomeController::class, 'getDataQuarterly'])->name('chart.quarterly');
Route::get('/topics/category', [HomeController::class, 'getTopicsData'])->name('charts.topics.category');


Route::get('/insert', function () {
    $comments = DB::table('comments_api')->get();
    $types = ['positive', 'negative', 'neutral', 'mixed'];

    $data=[];
    foreach($comments as $key=> $item){
        $data[]=[
            'ch_service' => $item->sn_service,
            'sn_id' => $item->sn_id,
            "ch_rate" => $types[rand(0,1)],
            "ch_amenddate" => '2022-01-19'
        ];
    }
    DB::table('chunks')->insert($data);
    return "done";
});
