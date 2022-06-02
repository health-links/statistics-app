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
        // return $this->comments()->wherePivot('type', 'negative')->count();
    }
    public function getPositiveTopics()
    {
        return DB::table('comment_topic')->where('topic_id', $this->t_id)->where('type', 'positive')->count();
        // return $this->comments()->wherePivot('type', 'positive')->count();
    }
}
