<?php

namespace App\Http\Controllers;

use App\Models\MasterTwoONine;
use App\Models\BreakfastTwoONine;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class BreakfastTwoONineController extends Controller
{
    private function rules($isUpdate=false): array {
        $opt = $isUpdate ? 'sometimes' : 'nullable';
        $int = [$opt,'integer','min:0'];
        $num = [$opt,'numeric','min:0'];

        $rules = [
            'total_actual_cover_in_house_adult' => $int,
            'total_actual_cover_in_house_child' => $int,
            'total_actual_cover_walk_in_adult'  => $int,
            'total_actual_cover_walk_in_child'  => $int,
            'total_actual_cover_event_adult'    => $int,
            'total_actual_cover_event_child'    => $int,
            'total_actual_cover_beo'            => $int,
            'food_revenue' => $num, 'beverage_revenue' => $num,
            'others_revenue' => $num, 'total_revenue' => $num,
            'vip_name' => [$opt,'string','max:100'],
            'vip_position' => [$opt,'string','max:100'],
            'notes' => [$opt,'string'],
            'staff_on_duty' => [$opt,'string'],
            'shangrila' => $int, 'jw_marriot' => $int, 'sheraton' => $int,
            // anak:
            'upsellings' => ['sometimes','array'],
            'upsellings.*.name' => ['required_with:upsellings','string','max:255'],
            'upsellings.*.pax'       => ['required_with:upsellings','integer','min:1'],

            'beverages' => ['sometimes','array'],
            'beverages.*.name' => ['required_with:beverages','string','max:255'],
            'beverages.*.pax'        => ['required_with:beverages','integer','min:1'],
        ];
        // for ($i=1; $i<=5; $i++) {
        //     $rules["upselling_{$i}"] = [$opt,'string','max:100'];
        //     $rules["upselling_{$i}_pax"] = $int;
        //     $rules["beverage_{$i}"] = [$opt,'string','max:100'];
        //     $rules["beverage_{$i}_pax"] = $int;
        // }
        return $rules;
    }

    public function index(MasterTwoONine $master) {
        return $master->breakfasts()->with(['upsellings', 'beverages'])->latest('id')->paginate(20);
    }

    public function store(Request $request, MasterTwoONine $master) {
        // $data = $request->validate($this->rules());
        // $data['master_two_o_nine_id'] = $master->id;
        // $row = BreakfastTwoONine::create($data);
        // return response()->json($row, 200);
        $validated = $request->validate($this->rules());
        $payload   = Arr::except($validated, ['upsellings','beverages']);

        $breakfast = DB::transaction(function () use ($payload, $validated, $master) {
            $b = $master->breakfasts()->create($payload);
            if (!empty($validated['upsellings'])) $b->upsellings()->createMany($validated['upsellings']);
            if (!empty($validated['beverages']))  $b->beverages()->createMany($validated['beverages']);
            return $b;
        });

        return response()->json($breakfast->load(['upsellings','beverages']), 200); // 200 sesuai preferensimu
    }

    public function show(MasterTwoONine $master, BreakfastTwoONine $breakfast) {
        abort_if($breakfast->master_two_o_nine_id !== $master->id, 404);
        return $breakfast->load(['upsellings','beverages']);
    }

    public function update(Request $request, MasterTwoONine $master, BreakfastTwoONine $breakfast) {
        // abort_if($breakfast->master_two_o_nine_id !== $master->id, 404);
        // $data = $request->validate($this->rules(true));
        // $breakfast->update($data);
        // return $breakfast;
        abort_if($breakfast->master_two_o_nine_id !== $master->id, 404);
        $validated = $request->validate($this->rules(true));
        $payload   = Arr::except($validated, ['upsellings','beverages']);

        $b = \DB::transaction(function () use ($payload, $validated, $breakfast) {
            $breakfast->update($payload);
            if (array_key_exists('upsellings', $validated)) {
                $breakfast->upsellings()->delete();
                $breakfast->upsellings()->createMany($validated['upsellings'] ?? []);
            }
            if (array_key_exists('beverages', $validated)) {
                $breakfast->beverages()->delete();
                $breakfast->beverages()->createMany($validated['beverages'] ?? []);
            }
            return $breakfast;
        });

        return $b->load(['upsellings','beverages']);
    }

    public function destroy(MasterTwoONine $master, BreakfastTwoONine $breakfast) {
        abort_if($breakfast->master_two_o_nine_id !== $master->id, 404);
        $breakfast->delete();
        return response()->json(['message'=>'Deleted']);
    }
}
