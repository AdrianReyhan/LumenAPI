<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return response()->json([
            'success' => true,
            'message' => 'List Semua Produk',
            'data'    => $products
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string', 
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description, 
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Produk Berhasil Dibuat',
            'data'    => $product
        ], 201);
    }

    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan',
            ], 404); 
        }

        return response()->json([
            'success' => true,
            'message' => 'Produk Ditemukan',
            'data'    => $product
        ], 200);
    }
}
