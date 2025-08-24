<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Position;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['code' => 'SADM', 'name' => 'Super Admin'],
            ['code' => 'MGR', 'name' => 'Manager'],
            ['code' => 'SPV', 'name' => 'Supervisor'],
            ['code' => 'STF', 'name' => 'Staff'],
        ];

        foreach ($rows as $r) {
            Position::updateOrCreate(
                ['code' => $r['code']],
                ['name' => $r['name']]
            );
        }
    }
}
