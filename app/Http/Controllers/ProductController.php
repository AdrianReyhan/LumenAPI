<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // Menampilkan semua produk
    public function index()
    {
        $products = Product::all();

        return response()->json([
            'success' => true,
            'message' => 'List Semua Produk',
            'data'    => $products
        ], 200);
    }

    // Menyimpan produk baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Semua Kolom Wajib Diisi!',
                'data'    => $validator->errors()
            ], 400);
        }

        $product = Product::create([
            'name'        => $request->input('name'),
            'description' => $request->input('description'),
        ]);

        if ($product) {
            return response()->json([
                'success' => true,
                'message' => 'Produk Berhasil Disimpan!',
                'data'    => $product
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Produk Gagal Disimpan!',
            ], 400);
        }
    }

    // Mengupdate produk berdasarkan ID
    public function update($id, Request $request)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan',
            ], 404);
        }

        // Validasi data menggunakan Validator
        $validator = Validator::make($request->all(), [
            'name'        => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid!',
                'errors'  => $validator->errors(),
            ], 400);
        }

        // Update atribut yang ada dalam request (hanya yang diberikan)
        if ($request->has('name')) {
            $product->name = $request->input('name');
        }

        if ($request->has('description')) {
            $product->description = $request->input('description');
        }

        $product->save();

        return response()->json([
            'success' => true,
            'message' => 'Produk Berhasil Diupdate!',
            'data'    => [
                'id'          => $product->id,
                'name'        => $product->name,
                'description' => $product->description,
                'link'        => "/products/{$product->id}",
            ],
        ], 200);
    }

    // Menampilkan produk berdasarkan ID
    public function show($id)
    {
        $product = Product::find($id);

        if ($product) {
            return response()->json([
                'success' => true,
                'message'   => 'Data Berhasil Ditemukan',
                'data'      => $product
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Produk Tidak Ditemukan!',
            ], 404);
        }
    }

    // Menghapus produk berdasarkan ID
    public function destroy($id)
    {
        $product = Product::whereId($id)->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan!',
            ], 404);
        }

        $product->delete();

        return response()->json([
            'success'   => true,
            'deleted_id' => $product->id,
        ], 200);
    }
}
