<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentApi extends Model
{
    use HasFactory;
    protected $table = 'comments_api';
    protected $primaryKey = 'sn_id';


    public function categories()
    {
        return $this->belongsToMany(CommentCategory::class, 'comment_category', 'comment_id', 'category_id');
    }

    public function topics()
    {
        return $this->belongsToMany(CommentTopics::class, 'comment_topic', 'comment_id', 'topic_id')->withPivot('type');
    }

    public function getNegativeTopics()
    {
        return $this->topics()->wherePivot('type', 'negative')->count();
    }
    public function getPositiveTopics()
    {
        return $this->topics()->wherePivot('type', 'positive')->count();
    }
    public function getNeutralTopics()
    {
        return $this->topics()->wherePivot('type', 'neutral')->count();
    }

    public function getMixedTopics()
    {
        return $this->topics()->wherePivot('type', 'mixed')->count();
    }





    // scope for filter data
    public function scopeFilterData($query, $request)
    {
        $query->when($request->service_id !== null, function ($q) use ($request) {
            return $q->where('sn_service', $request->service_id);
        });
        $query->when($request->client_id !== null, function ($q) use ($request) {
            return $q->where('sn_client', $request->client_id);
        });
        $query->when( $request->duration !== null,function ($q) use ($request) {
            return $q->where('sn_amenddate', '<', date('Y-m-d', strtotime('-' . $request->duration . ' days')));
        });
        $query->when($request->from !== null, function ($q) use ($request) {
            return $q->where('sn_date', '>=', $request->from);
        });
        $query->when($request->to !== null, function ($q) use ($request) {
            return $q->where('sn_date', '<=', $request->to);
        });
        return $query;
    }
}
