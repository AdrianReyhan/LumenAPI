<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostsController extends Controller
{

    public function index()
    {
        $posts = Post::all();

        return response()->json([
            'success' => true,
            'message' => 'List Semua Post',
            'data'    => $posts
        ], 200);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'   => 'required',
            'content' => 'required',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua Kolom Wajib Diisi!',
                'data'   => $validator->errors()
            ], 400);
        } else {

            $post = Post::create([
                'title'     => $request->input('title'),
                'content'   => $request->input('content'),
                'status'    => $request->input('status', 'draft'),
                'user_id'   => $request->input('user_id'),
            ]);

            if ($post) {
                return response()->json([
                    'success' => true,
                    'message' => 'Post Berhasil Disimpan!',
                    'data' => $post
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Post Gagal Disimpan!',
                ], 400);
            }
        }
    }

    public function update($id, Request $request)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post tidak ditemukan',
            ], 404);
        }

        // Validasi data menggunakan Validator
        $validator = Validator::make($request->all(), [
            'title'   => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'status'  => 'nullable|in:draft,published',
            'user_id' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid!',
                'errors'  => $validator->errors(),
            ], 400);
        }

        // Update atribut yang ada dalam request (hanya yang diberikan)
        if ($request->has('title')) {
            $post->title = $request->input('title');
        }

        if ($request->has('content')) {
            $post->content = $request->input('content');
        }

        if ($request->has('status')) {
            $post->status = $request->input('status');
        }

        if ($request->has('user_id')) {
            $post->user_id = $request->input('user_id');  
        }

        $post->save();

        return response()->json([
            'success' => true,
            'message' => 'Post Berhasil Diupdate!',
            'data'    => $post
        ], 200);
    }


    public function show($id)
    {
        $post = Post::find($id);

        if ($post) {
            return response()->json([
                'success' => true,
                'message'   => 'Data Berhasil Ditemukan',
                'data'      => $post
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Post Tidak Ditemukan!',
            ], 404);
        }
    }

    public function destroy($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post tidak ditemukan!',
            ], 404);
        }
        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post berhasil dihapus!',
            'deleted_id' => $post->id,
        ], 200);
    }
}
