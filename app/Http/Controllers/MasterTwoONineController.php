<?php

namespace App\Http\Controllers;

use App\Models\MasterTwoONine;
use Illuminate\Http\Request;

class MasterTwoONineController extends Controller
{
    public function index() {
        return MasterTwoONine::orderBy('date','desc')->paginate(20);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => ['required','string','max:100'],
            'date' => ['required','date'],
        ]);
        return response()->json(MasterTwoONine::create($data), 201);
    }

    public function show(MasterTwoONine $master) {
        return $master->load(['breakfasts','lunches','dinners']);
    }

    public function update(Request $request, MasterTwoONine $master) {
        $data = $request->validate([
            'name' => ['sometimes','string','max:100'],
            'date' => ['sometimes','date'],
        ]);
        $master->update($data);
        return $master;
    }

    public function destroy(MasterTwoONine $master) {
        $master->delete();
        return response()->json(['message'=>'Deleted']);
    }
}
