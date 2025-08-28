<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BeveragesTwoONine extends Model
{
    //
    use HasFactory;
    protected $fillable = ['name', 'pax'];
    public function beverageable() {
        return $this->morphTo();
    }
}
