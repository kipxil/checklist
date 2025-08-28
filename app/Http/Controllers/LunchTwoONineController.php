<?php

namespace App\Http\Controllers;

use App\Models\MasterTwoONine;
use App\Models\LunchTwoONine;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class LunchTwoONineController extends Controller
{
    private function rules($isUpdate=false): array {
        $opt = $isUpdate ? 'sometimes' : 'nullable';
        $int = [$opt,'integer','min:0'];
        $num = [$opt,'numeric','min:0'];

        $rules = [
            'thematic' => [$opt,'string','max:100'],
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
        return $master->lunches()->with(['upsellings', 'beverages'])->latest('id')->paginate(20);
    }

    public function store(Request $request, MasterTwoONine $master) {
        // $data = $request->validate($this->rules());
        // $data['master_two_o_nine_id'] = $master->id;
        // $row = LunchTwoONine::create($data);
        $validated = $request->validate($this->rules());
        $payload   = Arr::except($validated, ['upsellings','beverages']);

        $lunch = DB::transaction(function () use ($payload, $validated, $master) {
            $b = $master->lunches()->create($payload);
            if (!empty($validated['upsellings'])) $b->upsellings()->createMany($validated['upsellings']);
            if (!empty($validated['beverages']))  $b->beverages()->createMany($validated['beverages']);
            return $b;
        });

        return response()->json($lunch->load(['upsellings','beverages']), 200); // 200 sesuai preferensimu
    }

    public function show(MasterTwoONine $master, LunchTwoONine $lunch) {
        abort_if($lunch->master_two_o_nine_id !== $master->id, 404);
        return $lunch->load(['upsellings','beverages']);
    }

    public function update(Request $request, MasterTwoONine $master, LunchTwoONine $lunch) {
        // abort_if($lunch->master_two_o_nine_id !== $master->id, 404);
        // $data = $request->validate($this->rules(true));
        // $lunch->update($data);
        // return $lunch;
        abort_if($lunch->master_two_o_nine_id !== $master->id, 404);
        $validated = $request->validate($this->rules(true));
        $payload   = Arr::except($validated, ['upsellings','beverages']);

        $b = \DB::transaction(function () use ($payload, $validated, $lunch) {
            $lunch->update($payload);
            if (array_key_exists('upsellings', $validated)) {
                $lunch->upsellings()->delete();
                $lunch->upsellings()->createMany($validated['upsellings'] ?? []);
            }
            if (array_key_exists('beverages', $validated)) {
                $lunch->beverages()->delete();
                $lunch->beverages()->createMany($validated['beverages'] ?? []);
            }
            return $lunch;
        });

        return $b->load(['upsellings','beverages']);
    }

    public function destroy(MasterTwoONine $master, LunchTwoONine $lunch) {
        abort_if($lunch->master_two_o_nine_id !== $master->id, 404);
        $lunch->delete();
        return response()->json(['message'=>'Deleted']);
    }
}
