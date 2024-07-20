<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Image;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    public function index()
    {
        // Lấy tất cả các bình luận của một bài viết cụ thể
        $article = Article::find(1);
        $comments = $article->comments;
        // dd($comments);

        // Lấy tất cả các đánh giá của một video cụ thể
        $video = Video::find(1);
        $ratings = $video->ratings;
        // dd($ratings->toArray());

        // Lấy tất cả các bình luận của một người dùng cụ thể
        $user = User::find(1);
        $userComments = Comment::where('user_id', $user->id)->get();
        // dd($userComments);

        // Lấy trung bình đánh giá của một bài viết cụ thể
        $article2 = Article::find(2);
        $averageRating = $article2->ratings()->avg('rating');
        // dd($averageRating);

        // Lấy tất cả các bài viết, video, và hình ảnh được bình luận bởi một người dùng cụ thể
        $user = User::find(2);
        $userComments = Comment::where('user_id', $user->id)->get();
        $articlesCommented = $userComments->filter(function ($comment) {
            return $comment->commentable_type == Article::class;
        })->map(function ($comment) {
            return $comment->commentable;
        });
        // dd($articlesCommented);

        $videosCommented = $userComments->filter(function ($comment) {
            return $comment->commentable_type == Video::class;
        })->map(function ($comment) {
            return $comment->commentable;
        });
        // dd($videosCommented);

        $imagesCommented = $userComments->filter(function ($comment) {
            return $comment->commentable_type == Image::class;
        })->map(function ($comment) {
            return $comment->commentable;
        });
        // dd($imagesCommented);

        // Lấy danh sách các bài viết, video, và hình ảnh có đánh giá trung bình cao nhất

        $topRatedArticles = Article::with(['ratings' => function ($query) {
            $query->select(DB::raw('rateable_id, AVG(rating) as average_rating'))
                ->groupBy('rateable_id')
                ->orderBy('average_rating', 'desc')
                ->take(5);
        }])->get();
        dd($topRatedArticles);



    }
}
