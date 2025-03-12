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

        $post->save();

        return response()->json([
            'success' => true,
            'message' => 'Post Berhasil Diupdate!',
            'data' => [
                'id' => $post->id,
                'title' => $post->title,
                'content' => $post->content,
                'link' => "/posts/{$post->id}",
            ],
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
        $post = Post::whereId($id)->first();
        $post->delete();

        if ($post) {
            return response()->json([
                'success' => true,
                'message' => 'Post Berhasil Dihapus!',
                'id' => $post->id,
            ], 200);
        }
    }
}
