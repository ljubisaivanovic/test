<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Get all posts
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $count = Post::all()->count();

        //relations
        if ($request->with) {
            $posts = Post::with($request->with);
        } else {
            $posts = Post::query();
        }

        //filter
        if ($request->id) {
            $posts->where('id', $request->id);
        }

        if ($request->topic) {
            $posts->where('topic', 'LIKE', '%' . $request->topic . '%');
        }

        if ($request->created_at) {
            $posts->whereDate('created_at', $request->created_at);
        }

        if ($request->updated_at) {
            $posts->whereDate('updated_at', $request->updated_at);
        }

        //sort
        if ($request->sort && in_array($request->sort, ['id', 'created_at'])) {
            $sort = $request->sort;
        } else {
            $sort = 'id';
        }

        //sort by
        if ($request->direction && in_array($request->direction, ['asc', 'desc'])) {
            $direction = $request->direction;
        } else {
            $direction = 'asc';
        }

        //pagination
        $limit = $request->limit ?: 10;
        if ($request->page) {
            $data = $posts->orderBy($sort, $direction)->paginate($limit);
        } else {
            $data = $posts->orderBy($sort, $direction)->limit($limit)->get();
        }

        return response()->json([
            'result' => $data,
            'count' => $count
        ]);
    }

    /**
     * Create post
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
        ]);

        $validator = Validator::make($request->all(),
            [
                'topic' => 'required|unique:posts|max:255',
            ],
        );

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $post = Post::create($request->all());

        if ($post) {
            return response()->json([
                'result' => $post
            ], 201);
        }

        return response()->json([
            'message' => 'Internal server error.',
        ], 500);
    }

    /**
     * Delete post
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $post = Post::find($id);

        if ($post) {
            $res = $post->delete($id);
            if ($res) {
                return response()->json(true, 200);
            }

            return response()->json(false, 417);
        }

        return response()->json([
            'errors' => [
                "The post doesn't exist."
            ]
        ], 404);
    }
}
