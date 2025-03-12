<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function index($postId, Request $request)
    {
        // Cari post berdasarkan postId
        $post = Post::find($postId);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post tidak ditemukan!',
            ], 404);  
        }

        // Ambil komentar yang terkait dengan post ini dengan pagination
        $comments = $post->comments()
            ->paginate(10);  

        return response()->json([
            'data' => $comments->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'comment' => $comment->comment,
                    'post_id' => $comment->post_id,
                    'link' => "/comments/{$comment->id}",
                ];
            }),
            'total_count' => $comments->total(),
            'limit' => $comments->perPage(),
            'pagination' => [
                'first_page' => $comments->url(1),
                'last_page' => $comments->url($comments->lastPage()),
                'page' => $comments->currentPage(),
            ]
        ], 200);  
    }

    public function store($postId, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Komentar tidak valid!',
                'errors'  => $validator->errors(),
            ], 400);
        }

        // Cari post berdasarkan ID
        $post = Post::find($postId);
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post tidak ditemukan!',
            ], 404);
        }

        $comment = Comment::create([
            'post_id' => $post->id,
            'comment' => $request->input('comment'),
        ]);

        return response()->json([
            'id' => $comment->id,
            'post_id' => $comment->post_id,
            'comment' => $comment->comment,
            'link' => "/comments/{$comment->id}",
        ], 201);
    }

    public function show($id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Komentar tidak ditemukan!',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendapatkan Komen',
            'data' => [
                'id' => $comment->id,
                'post_id' => $comment->post_id,
                'comment' => $comment->comment,
                'link' => "/comments/{$comment->id}",
            ],   
        ], 200);
    }

    public function showPostComment($postId, $id)
    {
        $post = Post::find($postId);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post tidak ditemukan!',
            ], 404);
        }

        // Mencari komentar berdasarkan commentId dan postId
        $comment = $post->comments()->find($id);

        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Komentar tidak ditemukan!',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Menemukan Komen di Postingan',
            'data' => [
                'id' => $comment->id,
                'post_id' => $comment->post_id,
                'comment' => $comment->comment,
                'link' => "/comments/{$comment->id}",
            ]
        ], 200);
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Komentar tidak valid!',
                'errors'  => $validator->errors(),
            ], 400);
        }

        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Komentar tidak ditemukan!',
            ], 404);  
        }

        // Update komentar
        $comment->comment = $request->input('comment');
        $comment->save();

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil diupdate!',
            'data' => [
                'id' => $comment->id,
                'post_id' => $comment->post_id,
                'comment' => $comment->comment,
                'link' => "/comments/{$comment->id}",
            ],
        ], 200);  
    }
    public function updatePostComment($postId, $id, Request $request)
    {
        // Validasi input data komentar
        $validator = Validator::make($request->all(), [
            'comment' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Komentar tidak valid!',
                'errors'  => $validator->errors(),
            ], 400);
        }

        $post = Post::find($postId);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post tidak ditemukan!',
            ], 404);  
        }

        $comment = $post->comments()->find($id);

        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Komentar tidak ditemukan!',
            ], 404);  
        }

        // Update komentar
        $comment->comment = $request->input('comment');
        $comment->save();

        return response()->json([
            'success' => true,
            'message' => 'Komentar yang ada di postingan berhasil diupdate!',
            'data' => [
                'id' => $comment->id,
                'post_id' => $comment->post_id,
                'comment' => $comment->comment,
                'link' => "/comments/{$comment->id}",
            ],
        ], 200);  
    }

    public function destroy($id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Komentar tidak ditemukan!',
            ], 404);  
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'deleted_id' => $comment->id,
        ], 200);  // HTTP Status 200 OK
    }
}
