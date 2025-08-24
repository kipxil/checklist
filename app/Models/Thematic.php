<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Thematic extends Model
{
    //
    use HasFactory;

    // protected $table = 'thematic_two_o_nines';
    protected $fillable = ['name'];
}
