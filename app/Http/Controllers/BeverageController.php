<?php

namespace App\Http\Controllers;

use App\Models\Beverage;
use Illuminate\Http\Request;

class BeverageController extends Controller
{
    public function index()
    {
        return Beverage::orderBy('id', 'asc')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:100','unique:beverages,name']
        ]);
        return response()->json(Beverage::create($data), 200);
    }

    public function show(Beverage $beverage)
    {
        return $beverage;
    }

    public function update(Request $request, Beverage $beverage)
    {
        $data = $request->validate([
            'name' => ['required','string','max:100','unique:beverages,name,'.$beverage->id]
        ]);
        $beverage->update($data);
        return $beverage;
    }

    public function destroy(Beverage $beverage)
    {
        $beverage->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
