<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentCategory extends Model
{
    use HasFactory;
    protected $table = 'comments_categories';
    protected $primaryKey = 'c_id';

    public function comments()
    {
        return $this->belongsToMany(CommentApi::class, 'comment_category', 'category_id','comment_id');
    }

    public function getNg()
    {
        return $this->comments()->where('r_rate', '=', 'negative')->count();
    }

    public function getP()
    {
        return $this->comments()->where('r_rate', '=', 'positive')->count();
    }

    public function getNe()
    {
        return $this->comments()->where('r_rate', '=', 'neutral')->count();
    }

    public function getM()
    {
        return $this->comments()->where('r_rate', '=', 'mixed')->count();
    }

    public function scopeFilterData($query, $request)
    {
        $query->where('c_report', '=', '1');
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
        $query->when($request->from !== null, function ($q) {
            $q->whereHas('comments', function ($q) {
                return $q->where('sn_amenddate', '>=', request()->from);
            });
        });
        $query->when($request->to !== null, function ($q) {
            $q->whereHas('comments', function ($q) {
                return $q->where('sn_amenddate', '<=', request()->to);
            });
        });

        return $query;
    }


}
