<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    public function store(Request $request) {
        $id = Auth::id();

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'foto_ktp' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'massage' => 'Form belum lengkap',
            ], 422);
        } else {
            $imagePath = null;
            if ($request->hasFile('foto_ktp')) {
                $imageName = Str::random(34) . '.' . $request->file('foto_ktp')->getClientOriginalExtension();
                $request->file('foto_ktp')->move(storage_path('app/public/foto_ktp'), $imageName);
                $imagePath = $imageName;
            }

            $shop = Shop::create([
                'user_id' => $id,
                'name' => $request->input('name'),
                'foto_ktp' => $imagePath,
            ]);

            if ($shop) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengajuan Toko Berhasil',
                    'data'    => $shop,
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengajuan toko gagal',
                ], 400);
            }
        }
    }

    public function validasi(Request $request, $id) {
        $shop = Shop::whereId($id)->first();
        $validator = Validator::make($request->all(), [
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'massage' => 'Status wajib diisi',
            ], 422);
        } else {
            $shop->update([
                'user_id' => $shop->user_id,
                'name' => $shop->name,
                'foto_ktp' => $shop->foto_ktp,
                'status' => $request->input('status'),
            ]);

            if ($shop) {
                return response()->json([
                    'success' => true,
                    'message' => 'Toko Telah Diverivikasi',
                    'data'    => $shop,
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Toko Gagal Diverifikasi',
                ], 400);
            }
        }
    }
}