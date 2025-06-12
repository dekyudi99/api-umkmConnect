<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    // Admin Access
    public function index()
    {
        $user = User::all();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal',
            ], 200);
        } else {
            return response()->json([
                'success' => true,
                'massage' => 'List Semua User',
                'data' => $user,
            ]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|min:8',
            'bisnis_name'  => 'nullable',
            'path_image'   => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Semua Kolom Wajib Diisi!',
                'data'    => $validator->errors()
            ], 401);
        } else {
            $imagePath = null;
            if ($request->hasFile('path_image')) {
                $imageName = Str::random(34) . '.' . $request->file('path_image')->getClientOriginalExtension();
                $request->file('path_image')->move(storage_path('app/public/profile'), $imageName);
                $imagePath = $imageName;
            }

            $user = User::create([
                'name'        => $request->input('name'),
                'email'       => $request->input('email'),
                'password'    => Hash::make($request->input('password')),
                'bisnis_name' => $request->input('bisnis_name'),
                'path_image'  => $imagePath,
            ]);

            if ($user) {
                return response()->json([
                    'success' => true,
                    'message' => 'User Berhasil Disimpan!',
                    'data'    => $user
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'User Gagal Disimpan!',
                ], 400);
            }
        }
    }

    public function show($id)
    {
        $user = User::find($id);

        if ($user) {
            return response()->json([
                'success'   => true,
                'message'   => 'Detail User!',
                'data'      => $user
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User Tidak Ditemukan!',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan!',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'         => 'required',
            'email'        => 'required|email|unique:users,email,'.$user->id,
            'password'     => 'nullable|min:8',
            'bisnis_name'  => 'nullable',
            'path_image'   => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        $imagePath = $user->path_image;

        if ($request->hasFile('path_image')) {
            if ($user->path_image && Storage::disk('app/public/profile')->exists($user->path_image)) {
                Storage::disk('app/public/profile')->delete($user->path_image);
            }

            $imageName = Str::random(34) . '.' . $request->file('path_image')->getClientOriginalExtension();
            $request->file('path_image')->move(storage_path('app/public/profile'), $imageName);
            $imagePath = $imageName;
        }

        $userData = [
            'name'        => $request->input('name'),
            'email'       => $request->input('email'),
            'bisnis_name' => $request->input('bisnis_name'),
            'path_image'  => $imagePath,
        ];

        if ($request->has('password') && !empty($request->input('password'))) {
            $userData['password'] = Hash::make($request->input('password'));
        } else {
             unset($userData['password']);
        }


        $updated = $user->update($userData);

        if ($updated) {
            $updatedUser = User::find($id);
            return response()->json([
                'success' => true,
                'message' => 'User Berhasil Diupdate!',
                'data'    => $updatedUser,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User Gagal Diupdate!',
            ], 500); 
        }
    }

    public function destroy($id)
    {
        $user = User::whereId($id)->first();
        $user->delete();

        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'User Berhasil Dihapus!',
            ], 200);
        }
    }

    // Admin & Normal User Profile
    public function updateMe(Request $request) {
        $userId = Auth::id();
        $user = User::whereId($userId)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan!',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'         => 'required',
            'email'        => 'required|email|unique:users,email,'.$user->id,
            'password'     => 'nullable|min:8',
            'bisnis_name'  => 'nullable',
            'path_image'   => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        $imagePath = $user->path_image;

        if ($request->hasFile('path_image')) {
            if ($user->path_image && Storage::disk('profile')->exists($user->path_image)) {
                Storage::disk('app/public/profile')->delete($user->path_image);
            }

            $imageName = Str::random(34) . '.' . $request->file('path_image')->getClientOriginalExtension();
            $request->file('path_image')->move(storage_path('app/public/profile'), $imageName);
            $imagePath = $imageName;
        }

        $userData = [
            'name'        => $request->input('name'),
            'email'       => $request->input('email'),
            'bisnis_name' => $request->input('bisnis_name'),
            'path_image'  => $imagePath,
        ];

        if ($request->has('password') && !empty($request->input('password'))) {
            $userData['password'] = Hash::make($request->input('password'));
        } else {
             unset($userData['password']);
        }


        $updated = $user->update($userData);

        if ($updated) {
            $updatedUser = User::find($userId);
            return response()->json([
                'success' => true,
                'message' => 'User Berhasil Diupdate!',
                'data'    => $updatedUser,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User Gagal Diupdate!',
            ], 500); 
        }
    }

    public function deleteMe() {
        $userId = Auth::id();
        $user = User::whereId($userId)->first();
        $user->delete();

        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'User Berhasil Dihapus!',
            ], 200);
        }
    }
}