<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterTwoONine extends Model
{
    use HasFactory;

    // protected $table = 'master_two_o_nines';
    protected $fillable = ['name','date'];

    public function breakfasts() { return $this->hasMany(BreakfastTwoONine::class); }
    public function lunches()    { return $this->hasMany(LunchTwoONine::class); }
    public function dinners()    { return $this->hasMany(DinnerTwoONine::class); }
}
