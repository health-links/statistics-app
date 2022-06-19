<?php

namespace App\Http\Controllers;

use App\Services\CommentApiService;
use Illuminate\Http\Request;

class CommentApiController extends Controller
{
    private $commentService;
    public function __construct(CommentApiService $commentService)
    {
        $this->commentService = $commentService;
    }
    public function updateFlag(Request $request)
    {
        return $this->commentService->updateFlag($request);
    }
    public function updateBookmark(Request $request)
    {
        return $this->commentService->updateBookmark($request);
    }
}
