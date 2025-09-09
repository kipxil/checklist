<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            if (Schema::hasColumn('departments', 'work_at')) {
                $table->dropColumn('work_at');
            }
        });
    }

    public function down(): void
    {
        // Kembalikan kolom jika di-rollback
        Schema::table('departments', function (Blueprint $table) {
            $table->string('work_at')->nullable();
        });
    }
};
