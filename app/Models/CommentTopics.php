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
        return $this->belongsToMany(CommentApi::class, 'comment_topic', 'topic_id', 'comment_id')->withPivot('type');
    }
    // filter data
    public function scopeFilterData($query, $request)
    {
        $query->where('t_report', '=', '1');
        $query->when($request->service_id !== null, function ($q) {
            $q->whereHas('comments', function ($q) {

                return $q->where('sn_service', '=', request()->service_id);
            });
        });
        $query->when($request->client_id !== null, function ($q) {
            $q->whereHas('comments', function ($q) {
                return $q->where('sn_client', '=', request()->client_id);
            });
        });
        $query->when($request->category !== null && $request->category !== 'all', function ($q) {
            $q->whereHas('comments', function ($q) {
                return  $q->whereHas('categories', function ($q) {
                    return $q->where('category_id', '=', request()->category);
                });
            });

        });
        $query->when($request->category !== null && $request->category === 'all', function ($q) {
            return $q->whereHas('comments');
        });
        return $query;
    }
}
