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
        Schema::create('upsellings_two_o_nines', function (Blueprint $table) {
            $table->id();
            $table->morphs('upsellable');
            $table->string('name');
            $table->unsignedInteger('pax')->default(0);
            $table->timestamps();
            // $table->index(['upsellable_type', 'upsellable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upsellings_two_o_nines');
    }
};
