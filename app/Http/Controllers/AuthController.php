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
            'nik'      => ['required','string'],
            'password' => ['required','string'],
        ]);

        $user = User::with(['department','position'])->where('nik', $cred['nik'])->first();
        if (!$user || !Hash::check($cred['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 422);
        }

        // (Opsional) Revoke semua token lama
        $user->tokens()->delete();

        // ⬇️ Ambil daftar fitur/abilities dari helper
        $abilities = $user->features(); // contoh: ['restaurants'] untuk dept Restoran

        // ⬇️ BUAT TOKEN DENGAN ABILITIES SPESIFIK (bukan default ['*'])
        $token = $user->createToken('api', $abilities)->plainTextToken;

        return response()->json([
            'message'    => 'Login success',
            'token'      => $token,
            'token_type' => 'Bearer',
            'user'       => $user,
            'features'   => $abilities, // untuk FE
        ]);
    }

    public function me(Request $request)
    {
        $u = $request->user()->load(['department','position']);
        return response()->json([
            'id'          => $u->id,
            'nik'         => $u->nik,
            'name'        => $u->name,
            'email'       => $u->email,
            'department_id' => $u->department_id,
            'position_id'   => $u->position_id,
            'can_checklist' => (bool)$u->can_checklist,
            'admin'       => (bool)$u->admin,
            'created_at'  => $u->created_at,
            'updated_at'  => $u->updated_at,
            'department'  => $u->department,
            'position'    => $u->position,
            // ⬇️ kirim fitur & abilities token saat ini untuk verifikasi
            'features'    => $u->features(),
            'abilities'   => $u->currentAccessToken()?->abilities ?? [],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
