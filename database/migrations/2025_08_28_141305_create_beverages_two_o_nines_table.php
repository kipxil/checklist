<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('beverages_two_o_nines', function (Blueprint $table) {
            $table->id();
            $table->morphs('beverageable');
            $table->string('name');
            $table->unsignedInteger('pax')->default(0);
            $table->timestamps();
            // $table->index(['beverageable_type', 'beverageable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beverages_two_o_nines');
    }
};
