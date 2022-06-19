<?php

use App\Http\Controllers\CommentApiController;
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
Route::get('comments', [HomeController::class, 'getComments'])->name('comments.table');

// routes when clicking on a charts
Route::get('comments/types', [HomeController::class, 'getCommentsTypes'])->name('comments.types');
Route::get('comments/chunks', [HomeController::class, 'getCommentsChunks'])->name('comments.chunks');
Route::get('comments/trend', [HomeController::class, 'getCommentsTrend'])->name('comments.trend');
Route::get('comments/category', [HomeController::class, 'getCommentsCategory'])->name('comments.category');
Route::get('comments/topic', [HomeController::class, 'getCommentsTopic'])->name('comments.topic');
Route::get('comments/heatmap', [HomeController::class, 'getHeatMapComments'])->name('comments.heatmap');


Route::post('comments/updateFlag', [CommentApiController::class, 'updateFlag'])->name('comments.updateFlag');
Route::post('comments/updateBookmark', [CommentApiController::class, 'updateBookmark'])->name('comments.updateBookmark');


// Route::get('/insert', function () {
//     $types = ['positive', 'negative', 'neutral'];

//     $dates = [ '2021-12-05', '2021-12-12', '2021-12-15', '2021-12-20', '2021-12-25', '2021-12-30' ];

//     $arr = [];
//     for ($i = 0; $i < 2000; $i++) {
//         $arr[] = [
//             'sn_client' => 1,
//             'sn_service' => 'as',
//             'sn_survey_code' => 'test',
//             'sn_comment_field' => 'test',
//             'sn_rate' => 'test',
//             'sn_domain' => 'test',
//             'sn_comment' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book',
//             'sn_amenddate' => $dates[rand(0, 5)],
//             'sn_created' => now(),
//             'sn_month' =>12,
//             'sn_quarter' =>4,
//             'sn_year' => 2021,
//             'r_response' => '',
//             'r_rate' => 'neutral',
//             'r_cats' => ''
//         ];
//     }

//     DB::table('comments_api')->insert($arr);
// });
