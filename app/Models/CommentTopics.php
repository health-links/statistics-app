<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Http\Filters\CommentTopics\CommentTopicsFilter;

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
    public function scopeFilterData($query)
    {

        $query->where('t_report', '=', '1');
        $query->when(request()->filter['service_id'] !== null, function ($q) {
            $q->whereHas('comments', function ($q) {
                return $q->where('sn_service', '=',request()->filter['service_id']);
            });
        });
        $query->when(request()->filter['client_id'] !== null, function ($q) {
            $q->whereHas('comments', function ($q) {
                return $q->where('sn_client', '=',request()->filter['client_id']);
            });
        });
        // $query->when(request()->filter['category'] !== null &&request()->filter['category'] !== 'all', function ($q) {
        //     $q->whereHas('comments', function ($q) {
        //         return  $q->whereHas('categories', function ($q) {
        //             return $q->where('c_id',request()->filter['category']);
        //         });
        //     });

        // });
        // $query->when(request()->filter['category'] !== null &&request()->filter['category'] === 'all', function ($q) {
        //     return $q->whereHas('comments');
        // });
        return $query;
    }
}
