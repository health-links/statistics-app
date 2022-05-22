<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentCategory extends Model
{
    use HasFactory;
    protected $table = 'comments_categories';
}
