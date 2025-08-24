<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return User::with(['department','position'])
            ->orderBy('id','desc')->paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nik' => ['required','string','max:50','unique:users,nik'],
            'name' => ['required','string','max:100'],
            'email' => ['nullable','email','max:150','unique:users,email'],
            'password' => ['required','string','min:6'],
            'department_id' => ['nullable','exists:departments,id'],
            'position_id' => ['nullable','exists:positions,id'],
            'can_checklist' => ['boolean'],
            'admin' => ['boolean'],
        ]);

        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        return response()->json($user->load(['department','position']), 201);
    }

    public function show(User $user)
    {
        return $user->load(['department','position']);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'nik' => ['sometimes','string','max:50', Rule::unique('users','nik')->ignore($user->id)],
            'name' => ['sometimes','string','max:100'],
            'email' => ['nullable','email','max:150', Rule::unique('users','email')->ignore($user->id)],
            'password' => ['sometimes','string','min:6'],
            'department_id' => ['nullable','exists:departments,id'],
            'position_id' => ['nullable','exists:positions,id'],
            'can_checklist' => ['boolean'],
            'admin' => ['boolean'],
        ]);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);
        return $user->load(['department','position']);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
