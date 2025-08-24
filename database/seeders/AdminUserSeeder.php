<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Department;
use App\Models\Position;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan sudah ada department & position dari seeder sebelumnya
        $dept = Department::where('code', 'D-HOTEL')->first() ?? Department::first();
        $pos  = Position::where('code', 'P-MGR')->first() ?? Position::first(); // optional

        User::updateOrCreate(
            ['nik' => '0000'],
            [
                'name'          => 'Super Admin',
                'email'         => 'admin.super@example.com',
                'password'      => Hash::make('password123'), // ganti di produksi
                'admin'         => true,
                'can_checklist' => true,
                'department_id' => $dept?->id,             // include department
                'position_id'   => $pos?->id,              // optional: hapus jika tak diperlukan
            ]
        );
    }
}
