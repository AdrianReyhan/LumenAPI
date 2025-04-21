<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::all();

        return response()->json([
            'success' => true,
            'message' => 'List Semua Komentar',
            'data'    => $comments
        ], 200);
    }

    public function show($id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Komentar tidak ditemukan!'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Komentar ditemukan',
            'data'    => $comment
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comment'  => 'required|string|max:250',  
            'post_id'  => 'required|exists:posts,id',  
            'user_id'  => 'required|exists:users,id',  
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 400);
        }

        $comment = Comment::create([
            'comment'  => $request->input('comment'),
            'post_id'  => $request->input('post_id'),
            'user_id'  => $request->input('user_id'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil disimpan!',
            'data'    => $comment
        ], 201);
    }

    public function update($id, Request $request)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Komentar tidak ditemukan!'
            ], 404);
        }

        // Validate the data
        $validator = Validator::make($request->all(), [
            'comment'  => 'nullable|string|max:250',  
            'post_id'  => 'nullable|exists:posts,id', 
            'user_id'  => 'nullable|exists:users,id',  
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 400);
        }

        if ($request->has('comment')) {
            $comment->comment = $request->input('comment');
        }
        if ($request->has('post_id')) {
            $comment->post_id = $request->input('post_id');
        }
        if ($request->has('user_id')) {
            $comment->user_id = $request->input('user_id');
        }

        $comment->save();

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil diperbarui!',
            'data'    => $comment
        ], 200);
    }

    public function destroy($id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Komentar tidak ditemukan!'
            ], 404);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil dihapus!',
            'deleted_id' => $id
        ], 200);
    }
}
