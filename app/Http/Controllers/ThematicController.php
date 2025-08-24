<?php

namespace App\Http\Controllers;

use App\Models\Thematic;
use Illuminate\Http\Request;

class ThematicCOntroller extends Controller
{
    public function index() { return Thematic::orderBy('name')->get(); }

    public function store(Request $request) {
        $data = $request->validate(['name'=>['required','string','max:100','unique:thematics,name']]);
        return response()->json(Thematic::create($data), 201);
    }

    public function show(Thematic $thematic) { return $thematic; }

    public function update(Request $request, Thematic $thematic) {
        $data = $request->validate(['name'=>['required','string','max:100','unique:thematics,name,'.$thematic->id]]);
        $thematic->update($data);
        return $thematic;
    }

    public function destroy(Thematic $thematic) {
        $thematic->delete();
        return response()->json(['message'=>'Deleted']);
    }
}
