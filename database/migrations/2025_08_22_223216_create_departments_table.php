<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            // "name (hotel/restaurant)" --> kita pakai string biasa agar fleksibel
            $table->string('name');
            $table->string('work_at')->nullable(); // lokasi/keterangan kerja
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('departments');
    }
};
