<?php

namespace App\Http\Controllers;

use App\Models\Upselling;
use Illuminate\Http\Request;

class UpsellingController extends Controller
{
    public function index()
    {
        return Upselling::orderBy('id', 'asc')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:100','unique:upsellings,name']
        ]);
        return response()->json(Upselling::create($data), 200); // 200 sesuai preferensimu
    }

    public function show(Upselling $upselling)
    {
        return $upselling;
    }

    public function update(Request $request, Upselling $upselling)
    {
        $data = $request->validate([
            'name' => ['required','string','max:100','unique:upselling_two_o_nines,name,'.$upselling->id]
        ]);
        $upselling->update($data);
        return $upselling;
    }

    public function destroy(Upselling $upselling)
    {
        $upselling->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
