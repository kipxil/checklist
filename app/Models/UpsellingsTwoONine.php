<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UpsellingsTwoONine extends Model
{
    //
    use HasFactory;

    protected $fillable = ['name', 'pax'];
    public function upsellable() {
        return $this->morphTo();
    }
}
