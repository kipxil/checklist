<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique();           // login pakai NIK
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('password');

            $table->foreignId('department_id')->nullable()
                  ->constrained('departments')->nullOnDelete();
            $table->foreignId('position_id')->nullable()
                  ->constrained('positions')->nullOnDelete();

            $table->boolean('can_checklist')->default(false);
            $table->boolean('admin')->default(false);

            $table->rememberToken();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('users');
    }
};
