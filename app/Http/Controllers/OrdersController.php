<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Products;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{
    public function index()
    {
        $order = Orders::all();

        if ($order) {
            return response()->json([
                'success' => true,
                'massage' => 'Berhasil mengambil semua data',
                'data' => $order,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'massage' => 'Gagal mengambil semua data',
            ], 400);
        }
    }

    public function myOrder()
    {
        $user_id = Auth::id();
        $order = Orders::where('user_id', $user_id)->get();

        if ($order) {
            return response()->json([
                'success' => true,
                'massage' => 'Berhasil mengambil semua data',
                'data' => $order,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'massage' => 'Gagal mengambil semua data',
            ], 400);
        }
    }

    public function store(Request $request, $id)
    {
        $user_id = Auth::id();

        $validator = Validator::make($request->all(),[
            'quantity' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'massage' => 'Isi Kuantitasnya dong',
            ], 401);
        } else {
            $product = Products::whereId($id)->first();
            
            $order = Orders::create([
                'user_id' => $user_id,
                'product_id' => $id,
                'quantity' => $request->input('quantity'),
                'total_price' => $product->price*$request->input('quantity'),
            ]);

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'massage' => 'Gagal memesan product',
                ], 400);
            } else {
                return response()->json([
                    'success' => true,
                    'massage' => 'Pesanan berhasil',
                    'data' => $order,
                ], 200);
            }
        }
    }

    public function update(Request $request, $id)
    {
        $user_id = Auth::id();

        $validator = Validator::make($request->all(),[
            'quantity' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'massage' => 'Isi Kuantitasnya dong',
            ], 401);
        } else {
            $product = Products::whereId($id)->first();
            
            $order = Orders::update([
                'user_id' => $user_id,
                'product_id' => $id,
                'quantity' => $request->input('quantity'),
                'total_price' => $product->price*$request->input('quantity'),
            ]);

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'massage' => 'Gagal update product',
                ], 400);
            } else {
                return response()->json([
                    'success' => true,
                    'massage' => 'Pesanan berhasil diupdate',
                    'data' => $order,
                ], 200);
            }
        }
    }

    public function destroy($id)
    {
        $order = Orders::whereId($id)->first();
        $order->delete();

        if (!$order) {
            return response()->json([
                'success' => false,
                'massage' => 'Gagal Menghapus pesanan',
            ], 400);
        } else {
            return response()->json([
                'success' => true,
                'massage' => 'Berhasil Menghapus data',
            ], 200);
        }
    }
}