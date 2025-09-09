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
        Schema::table('master_two_o_nines', function (Blueprint $table) {
            // boolean di MySQL = TINYINT(1). Default: false (0).
            $table->boolean('approval')->default(false)->after('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_two_o_nines', function (Blueprint $table) {
            $table->dropColumn('approval');
        });
    }
};
