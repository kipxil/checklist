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
        Schema::create('breakfast_two_o_nines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_two_o_nine_id')
                  ->constrained('master_two_o_nines')
                  ->cascadeOnDelete();

            // covers
            $table->unsignedInteger('total_actual_cover_in_house_adult')->default(0);
            $table->unsignedInteger('total_actual_cover_in_house_child')->default(0);
            $table->unsignedInteger('total_actual_cover_walk_in_adult')->default(0);
            $table->unsignedInteger('total_actual_cover_walk_in_child')->default(0);
            $table->unsignedInteger('total_actual_cover_event_adult')->default(0);
            $table->unsignedInteger('total_actual_cover_event_child')->default(0);
            $table->unsignedInteger('total_actual_cover_beo')->default(0);

            // revenue
            $table->decimal('food_revenue', 15, 2)->default(0);
            $table->decimal('beverage_revenue', 15, 2)->default(0);
            $table->decimal('others_revenue', 15, 2)->default(0);
            $table->decimal('total_revenue', 15, 2)->default(0);

            // upselling 1..5 (item + pax)
            for ($i=1; $i<=5; $i++) {
                $table->string("upselling_{$i}")->nullable();
                $table->unsignedInteger("upselling_{$i}_pax")->default(0);
            }
            // beverage 1..5 (item + pax)
            for ($i=1; $i<=5; $i++) {
                $table->string("beverage_{$i}")->nullable();
                $table->unsignedInteger("beverage_{$i}_pax")->default(0);
            }

            // vip, notes, staff
            $table->string('vip_name')->nullable();
            $table->string('vip_position')->nullable();
            $table->text('notes')->nullable();
            $table->string('staff_on_duty')->nullable();

            // competitor
            $table->unsignedInteger('shangrila')->default(0);
            $table->unsignedInteger('jw_marriot')->default(0);
            $table->unsignedInteger('sheraton')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('breakfast_two_o_nines');
    }
};
