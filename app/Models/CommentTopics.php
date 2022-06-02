<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommentTopics extends Model
{
    use HasFactory;
    protected $table = 'comments_topics';
    protected $primaryKey = 't_id';
    public function comments()
    {
        return $this->belongsToMany(CommentApi::class,'comment_topic', 'topic_id', 'comment_id')->withPivot('type');
    }

    public function getNegativeTopics()
    {
        return DB::table('comment_topic')->where('topic_id', $this->t_id)->where('type', 'negative')->count();

    }
    public function getPositiveTopics()
    {
        return DB::table('comment_topic')->where('topic_id', $this->t_id)->where('type', 'positive')->count();

    }

    // filter data
    public function scopeFilterData($query, $request)
    {
        $query->where('t_report', '=', '1');
        $query->when($request->service_id !== null, function ($q) {
            $q->whereHas('comments', function ($q2) {
                return $q2->where('sn_service', '=', request()->service_id);
            });
        });
        $query->when($request->client_id !== null, function ($q) {
            $q->whereHas('comments', function ($q2) {
                return $q2->where('sn_client', '=', request()->client_id);;
            });
        });
        $query->when($request->category !== null && $request->category !== 'all', function ($q) {

            $q->whereHas('comments', function ($q2) {
                $q2->whereHas('categories', function ($q3) {
                    return $q3->where('c_id', request()->category)->where('c_report', '=', 1);
                });
            });
        });
        $query->when($request->category !== null && $request->category === 'all', function ($q) {
            return $q->whereHas('comments');
        });
        return $query;
    }
}
