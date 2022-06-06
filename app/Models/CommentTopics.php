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

    public function negative()
    {
        return $this->comments()->wherePivot('type', '=', 'negative');
    }

    public function positive()
    {
        return $this->comments()->wherePivot('type', '=', 'positive');
    }

}
