<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $cred = $request->validate([
            'nik' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // $user = User::where('nik', $cred['nik'])->first();
        $user = User::with(['department', 'position'])->where('nik', $cred['nik'])->first();
        if (!$user || !Hash::check($cred['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 422);
        }

        // Hapus token lama (opsional)
        $user->tokens()->delete();

        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'message' => 'Login success',
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    public function me(Request $request)
    {
        return response()->json($request->user()->load(['department','position']));
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
