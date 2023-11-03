<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $post_data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|min:8'
        ]);
        try {
            $user = User::create([
                'name' => $post_data['name'],
                'email' => $post_data['email'],
                'password' => Hash::make($post_data['password']),
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Akun Berhasil Terdaftar',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal',
                'success' => true,
                'data' => $e
            ]);
        }
    }

    public function login(Request $request)
    {
        try {
            if (!\Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'message' => 'Invalid login details'
                ], 401);
            }
            $user = User::where('email', $request['email'])->firstOrFail();
            $token = $user->createToken('authToken')->plainTextToken;
            return response()->json([
                'message' => true,
                'data' => $user,
                'role' => $user->role,
                'access_token' => $token,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal',
                'success' => true,
                'data' => $e
            ]);
        }
    }
}
