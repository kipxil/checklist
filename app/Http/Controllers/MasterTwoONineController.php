<?php

namespace App\Http\Controllers;

use App\Models\MasterTwoONine;
use Illuminate\Http\Request;

class MasterTwoONineController extends Controller
{
    public function index(Request $request)
    {
        $q = MasterTwoONine::query()->latest('id');

        // tri-state: ?approved=1 → hanya approved, ?approved=0 → hanya unapproved,
        // tanpa parameter → semua
        if ($request->has('approved')) {
            $q->where('approval', $request->boolean('approved'));
        }

        return $q->paginate(20);
    }
    // public function index()
    // {
    //     return \App\Models\MasterTwoONine::query()
    //         // hanya master yang punya minimal 1 relasi di masing-masing ini
    //         ->has('breakfasts', '>=', 1)
    //         ->has('lunches', '>=', 1)
    //         ->has('dinners', '>=', 1)
    //         ->withCount(['breakfasts','lunches','dinners']) // opsional: tampilkan count
    //         ->orderBy('date', 'desc')
    //         ->paginate(20);
    // }

    // public function store(Request $request)
    // {
    //     $data = $request->validate([
    //         'name' => ['required', 'string', 'max:100'],
    //         'date' => ['required', 'date'],
    //         'approval' => ['sometimes', 'boolean'], // default false
    //     ]);
    //     return response()->json(MasterTwoONine::create($data));
    // }

    public function store(Request $request)
    {
        // 1. Validasi data input
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'date' => ['required', 'date'],
        ]);

        // 2. Logika approval otomatis berdasarkan position_id user
        $user = $request->user();
        if (in_array($user->position_id, [1, 2, 3])) {
            $data['approval'] = true; // Langsung disetujui
        } else {
            $data['approval'] = false; // Perlu persetujuan manual nanti
        }

        // 3. Buat data baru
        $master = MasterTwoONine::create($data);
        return response()->json($master, 201);
    }

    public function show(MasterTwoONine $master)
    {
        // return $master->load(['breakfasts','lunches','dinners']);
        return $master->load([
            'breakfasts.upsellings',
            'breakfasts.beverages',
            'lunches.upsellings',
            'lunches.beverages',
            'dinners.upsellings',
            'dinners.beverages',
        ]);
    }

    // public function update(Request $request, MasterTwoONine $master)
    // {
    //     $data = $request->validate([
    //         'name' => ['sometimes', 'string', 'max:100'],
    //         'date' => ['sometimes', 'date'],
    //         'approval' => ['sometimes', 'boolean'],
    //     ]);
    //     $master->update($data);
    //     return $master;
    // }

    public function update(Request $request, MasterTwoONine $master)
    {
        // 1. Otorisasi: Hanya user dengan position_id 1, 2, atau 3 yang boleh update.
        $user = $request->user();
        if (!in_array($user->position_id, [1, 2, 3])) {
            return response()->json(['message' => 'Anda tidak memiliki izin untuk mengubah data ini.'], 403);
        }

        // 2. Validasi data input
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:100'],
            'date' => ['sometimes', 'date'],
            'approval' => ['sometimes', 'boolean'],
        ]);

        // 3. Update data
        $master->update($data);
        return $master;
    }

    public function destroy(MasterTwoONine $master)
    {
        $master->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
