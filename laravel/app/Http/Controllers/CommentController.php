<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Get all comments
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $count = Comment::all()->count();

        if ($request->with) {
            $comments = Comment::with($request->with);
        } else {
            $comments = Comment::query();
        }

        //filter
        if ($request->id) {
            $comments->where('id', $request->id);
        }

        if ($request->content) {
            $comments->where('content', 'LIKE', '%' . $request->content . '%');
        }

        if ($request->created_at) {
            $comments->whereDate('created_at', $request->created_at);
        }

        if ($request->updated_at) {
            $comments->whereDate('updated_at', $request->updated_at);
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
            $data = $comments->orderBy($sort, $direction)->paginate($limit);
        } else {
            $data = $comments->orderBy($sort, $direction)->limit($limit)->get();
        }

        return response()->json([
            'result' => $data,
            'count' => $count
        ]);
    }

    /**
     * Create comment
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $content = $request->post('content');

        // Adding abbreviation to the request input bag in order to validate it later on
        if ($content) {
            $request->request->add([
                'abbreviation' => implode('', array_map(function ($word) {
                    return strtolower($word[0]);
                }, explode(' ', $content)))
            ]);
        }

        $validator = Validator::make($request->all(),
            [
                'post_id' => 'required|exists:posts,id',
                'content' => 'required',
                'abbreviation' => 'unique:comments,abbreviation'
            ],
            [
                'post_id.exists' => "Post with provided id doesn't exist.",
                'abbreviation.unique' => 'Comment with provided abbreviation already exists.'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $comment = Comment::create($request->all());

        if ($comment) {
            return response()->json([
                'result' => $comment,
            ], 201);
        }

        return response()->json([
            'message' => 'Internal server error.',
        ], 500);
    }

    /**
     * Delete comment
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $comment = Comment::find($id);

        if ($comment) {
            $res = $comment->delete($id);

            if ($res) {
                return response()->json(true, 200);
            }

            return response()->json(false, 417);
        }

        return response()->json([
            'error' => [
                "The comment doesn't exist."
            ]
        ], 404);
    }
}
