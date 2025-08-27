<?php

namespace App\Http\Controllers;

use App\Models\MasterTwoONine;
use App\Models\DinnerTwoONine;
use Illuminate\Http\Request;

class DinnerTwoONineController extends Controller
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
        ];
        for ($i=1; $i<=5; $i++) {
            $rules["upselling_{$i}"] = [$opt,'string','max:100'];
            $rules["upselling_{$i}_pax"] = $int;
            $rules["beverage_{$i}"] = [$opt,'string','max:100'];
            $rules["beverage_{$i}_pax"] = $int;
        }
        $rules += [
            'vip_name' => [$opt,'string','max:100'],
            'vip_position' => [$opt,'string','max:100'],
            'notes' => [$opt,'string'],
            'staff_on_duty' => [$opt,'string'],
            'shangrila' => $int, 'jw_marriot' => $int, 'sheraton' => $int,
        ];
        return $rules;
    }

    public function index(MasterTwoONine $master) {
        return $master->dinners()->latest('id')->paginate(20);
    }

    public function store(Request $request, MasterTwoONine $master) {
        $data = $request->validate($this->rules());
        $data['master_two_o_nine_id'] = $master->id;
        $row = DinnerTwoONine::create($data);
        return response()->json($row, 200);
    }

    public function show(MasterTwoONine $master, DinnerTwoONine $dinner) {
        abort_if($dinner->master_two_o_nine_id !== $master->id, 404);
        return $dinner;
    }

    public function update(Request $request, MasterTwoONine $master, DinnerTwoONine $dinner) {
        abort_if($dinner->master_two_o_nine_id !== $master->id, 404);
        $data = $request->validate($this->rules(true));
        $dinner->update($data);
        return $dinner;
    }

    public function destroy(MasterTwoONine $master, DinnerTwoONine $dinner) {
        abort_if($dinner->master_two_o_nine_id !== $master->id, 404);
        $dinner->delete();
        return response()->json(['message'=>'Deleted']);
    }
}
