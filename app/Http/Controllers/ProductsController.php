<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    public function index()
    {
        $product = Products::all();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal',
            ], 200);
        } else {
            return response()->json([
                'success' => true,
                'massage' => 'List Semua Product',
                'data' => $product,
            ]);
        }
    }

    public function myProducts()
    {
        $id = Auth::id();
        $product = Products::where('user_id', $id)->get();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal',
            ], 200);
        } else {
            return response()->json([
                'success' => true,
                'massage' => 'List Semua Product Anda',
                'data' => $product,
            ]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'        => 'required',
            'description'  => 'required',
            'price'        => 'required|numeric',
            'stock'        => 'required|numeric',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal!',
                'data'    => $validator->errors()
            ], 401);
        } else {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imageName = Str::random(34) . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(storage_path('app/public/product'), $imageName);
                $imagePath = $imageName;
            }

            $id_user = Auth::id();

            $product = Products::create([
                'user_id'     => $id_user,
                'title'       => $request->input('title'),
                'description' => $request->input('description'),
                'price'       => $request->input('price'),
                'stock'        => $request->input('stock'),
                'image'       => $imagePath,
            ]);

            if ($product) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product Berhasil Disimpan!',
                    'data'    => $product,
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Product Gagal Disimpan!',
                ], 400);
            }
        }
    }

    public function show($id)
    {
        $product = Products::find($id);

        if ($product) {
            return response()->json([
                'success'   => true,
                'message'   => 'Detail Product!',
                'data'      => $product,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Product Tidak Ditemukan!',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $product = Products::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product tidak ditemukan!',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title'        => 'required',
            'description'  => 'required',
            'price'        => 'required|numeric',
            'stock'        => 'required|numeric',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        $imagePath = $product->image;

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('app/public/product')->exists($product->image)) {
                Storage::disk('app/public/profile')->delete($product->image);
            }

            $imageName = Str::random(34) . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(storage_path('app/public/product'), $imageName);
            $imagePath = $imageName;
        }

        $id_user = Auth::id();

        $productData = [
            'user_id'     => $id_user,
            'title'       => $request->input('title'),
            'description' => $request->input('description'),
            'price'       => $request->input('price'),
            'stok'        => $request->input('stok'),
            'image'       => $imagePath,
        ];

        $updated = $product->update($productData);

        if ($updated) {
            $updatedProduct = Products::find($id);
            return response()->json([
                'success' => true,
                'message' => 'Product Berhasil Diupdate!',
                'data'    => $updatedProduct,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Product Gagal Diupdate!',
            ], 500); 
        }
    }

    public function destroy($id)
    {
        $product = Products::whereId($id)->first();
        $product->delete();

        if ($product) {
            return response()->json([
                'success' => true,
                'message' => 'Product Berhasil Dihapus!',
            ], 200);
        }
    }
}