<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentApi extends Model
{
    use HasFactory;
    protected $table = 'comments_api';
    protected $primaryKey = 'sn_id';
}
