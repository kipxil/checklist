<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        foreach (['breakfast_two_o_nines','lunch_two_o_nines','dinner_two_o_nines'] as $table) {
            Schema::table($table, function (Blueprint $t) {
                $cols = [];
                for ($i=1; $i<=5; $i++) {
                    $cols[] = "upselling_{$i}";
                    $cols[] = "upselling_{$i}_pax";
                    $cols[] = "beverage_{$i}";
                    $cols[] = "beverage_{$i}_pax";
                }
                $t->dropColumn($cols);
            });
        }
    }
    public function down(): void {
        foreach (['breakfast_two_o_nines','lunch_two_o_nines','dinner_two_o_nines'] as $table) {
            Schema::table($table, function (Blueprint $t) {
                for ($i=1; $i<=5; $i++) {
                    $t->string("upselling_{$i}")->nullable();
                    $t->unsignedInteger("upselling_{$i}_pax")->default(0);
                }
                for ($i=1; $i<=5; $i++) {
                    $t->string("beverage_{$i}")->nullable();
                    $t->unsignedInteger("beverage_{$i}_pax")->default(0);
                }
            });
        }
    }
};
