<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // return User::with(['department','position'])
        //     ->orderBy('id','desc')->paginate(20);
        $q = User::query()
            ->with([
                'department:id,code,name',
                'position:id,code,name'
            ]);

        // Filter berdasarkan ID integer (bisa multi dengan koma)
        if ($request->filled('work_at_ids')) { // Ganti nama parameter agar lebih jelas
            // 1. Pecah string ID menjadi array
            $values = explode(',', $request->query('work_at_ids'));

            // 2. Pastikan semua elemen adalah integer dan > 0
            $integerIDs = array_filter(
                array_map('intval', $values),
                fn($id) => $id > 0
            );

            if (!empty($integerIDs)) {
                // 3. Langsung filter di kolom 'work_at' pada tabel users
                $q->whereIn('work_at', $integerIDs);
            }
        }

        // // LIKE (case-insensitive tergantung collation)
        // if ($request->filled('work_at_like')) {
        //     $like = $request->query('work_at_like');
        //     $q->whereHas('department', function ($sub) use ($like) {
        //         $sub->where('work_at', 'like', "%{$like}%");
        //     });
        // }

        $perPage  = min(max((int) $request->query('per_page', 20), 1), 100);
        $direction = strtolower($request->query('direction', 'asc')) === 'desc' ? 'desc' : 'asc';

        return $q->orderBy('id', $direction)->paginate($perPage)->appends($request->query());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nik' => ['required', 'string', 'max:50', 'unique:users,nik'],
            'name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'position_id' => ['nullable', 'exists:positions,id'],
            'work_at' => ['nullable', 'integer', 'min:0'],
            'can_checklist' => ['boolean'],
            'admin' => ['boolean'],
        ]);

        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        return response()->json($user->load(['department', 'position']), 201);
    }

    public function show(User $user)
    {
        return $user->load(['department', 'position']);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'nik' => ['sometimes', 'string', 'max:50', Rule::unique('users', 'nik')->ignore($user->id)],
            'name' => ['sometimes', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['sometimes', 'string', 'min:6'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'position_id' => ['nullable', 'exists:positions,id'],
            'work_at' => ['nullable', 'integer', 'min:0'],
            'can_checklist' => ['boolean'],
            'admin' => ['boolean'],
        ]);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);
        return $user->load(['department', 'position']);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
