<?php

namespace App\Http\Controllers;

use App\Http\Resources\ErrorResource;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Http\Resources\SuccessResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            "title" => 'required|string|max:255',
            "body" => 'required|string',
        ]);

        $user = $request->user();
        $user_ip = $request->ip();
        $user_id = $user->id;

        $post = Post::create([
            'title'=> $data['title'],
            'body'=> $data['body'],
            'user_id'=> $user_id,
            'user_ip' => $user_ip,
        ]);

        if ($post) {
            return new SuccessResource([
                'status_code' => 200,
                'success_code' => 1002,
                'message' => 'پست شما با موفقیت ساخته شد',
            ]);
        }

        return new ErrorResource([
            'status_code' => 500,
            'error_code' => 2003,
            'message' => 'خطای ناشناخته ای رخ داد'
        ]);
    }

    public function update(Request $request, $post_id)
    {
        $user = $request->user();

        $post = Post::where('id', $post_id)->first();

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        if (!$post) {
            return new ErrorResource([
                'status_code' => 404,
                'error_code' => 2004,
                'message' => 'پست مورد نظر یافت نشد',
            ]);
        }

        if ($post->user_id != $user->id) {
            return new ErrorResource([
                'status_code' => 403,
                'error_code' => 4003,
                'message' => 'شما نمیتوانید پست افراد دیگر را تغییر دهید'
            ]);
        }

        $result = $post->update([
            'title' => $data['title'],
            'body'=> $data['body'],
            'user_id' => $user->id,
            'user_ip' => $request->ip(),
        ]);

        if ($result) {
            return new SuccessResource([
                'status_code' => 200,
                'success_code' => 1003,
                'message' => 'پست شما با موفقیت آپدیت شد',
            ]);
        }

        return new ErrorResource([
            'status_code' => 500,
            'error_code' => 2005,
            'message' => 'خطای ناشناخته ای رخ داد'
        ]);
    }

    public function all()
    {
        $posts = Post::all();
        return new PostCollection($posts);
    }

    public function show($post_id) {
        $post = Post::where('id', $post_id)->first();

        if (!$post) {
            return new ErrorResource([
                'status_code' => 404,
                'error_code' => 2004,
                'message' => 'پست مورد نظر یافت نشد',
            ]);
        }

        return new PostResource($post);
    }

    public function delete(Request $request, $post_id)
    {
        $user = $request->user();

        $post = Post::where('id', $post_id)->first();

        if (!$post) {
            return new ErrorResource([
                'status_code' => 404,
                'error_code' => 2004,
                'message' => 'پست مورد نظر یافت نشد',
            ]);
        }

        if ($post->user_id != $user->id) {
            return new ErrorResource([
                'status_code' => 403,
                'error_code' => 4003,
                'message' => 'شما نمیتوانید پست افراد دیگر را پاک کنید'
            ]);
        }

        $result = $post->delete();
        
        if ($request)
        {
            return new SuccessResource([
                'status_code' => 200,
                'success_code' => 1007,
                'message' => 'پست شما با موفقیت پاک شد',
            ]);
        }

    }
}
