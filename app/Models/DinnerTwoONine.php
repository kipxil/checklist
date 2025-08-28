<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DinnerTwoONine extends Model
{
    use HasFactory;

    // protected $table = 'dinner_two_o_nines';
    protected $fillable = [
        'master_two_o_nine_id','thematic',
        'total_actual_cover_in_house_adult','total_actual_cover_in_house_child',
        'total_actual_cover_walk_in_adult','total_actual_cover_walk_in_child',
        'total_actual_cover_event_adult','total_actual_cover_event_child',
        'total_actual_cover_beo',
        'food_revenue','beverage_revenue','others_revenue','total_revenue',
        'upselling_1','upselling_1_pax','upselling_2','upselling_2_pax','upselling_3','upselling_3_pax',
        'upselling_4','upselling_4_pax','upselling_5','upselling_5_pax',
        'beverage_1','beverage_1_pax','beverage_2','beverage_2_pax','beverage_3','beverage_3_pax',
        'beverage_4','beverage_4_pax','beverage_5','beverage_5_pax',
        'vip_name','vip_position','notes','staff_on_duty',
        'shangrila','jw_marriot','sheraton'
    ];

    public function masterTwoONine() {
        return $this->belongsTo(MasterTwoONine::class, 'master_two_o_nine_id');
    }
    public function upsellings() {
        return $this->morphMany(UpsellingsTwoONine::class, 'upsellable');
    }
    public function beverages() {
        return $this->morphMany(BeveragesTwoONine::class, 'beverageable');
    }
    protected static function booted() {
        static::deleting(function ($dinner) {
            $dinner->upsellings()->delete();
            $dinner->beverages()->delete();
        });
    }
}
