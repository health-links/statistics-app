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


}
