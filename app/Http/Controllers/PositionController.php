<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        return Position::orderBy('id','desc')->paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required','string','max:50','unique:positions,code'],
            'name' => ['required','string','max:100'],
        ]);
        $pos = Position::create($data);
        return response()->json($pos, 201);
    }

    public function show(Position $position)
    {
        return $position;
    }

    public function update(Request $request, Position $position)
    {
        $data = $request->validate([
            'code' => ['sometimes','string','max:50','unique:positions,code,'.$position->id],
            'name' => ['sometimes','string','max:100'],
        ]);
        $position->update($data);
        return $position;
    }

    public function destroy(Position $position)
    {
        $position->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
