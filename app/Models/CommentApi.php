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

    // chuncks
    public function chunksData()
    {
        return $this->hasMany(Chunk::class, 'sn_id', 'sn_id');
    }


}
