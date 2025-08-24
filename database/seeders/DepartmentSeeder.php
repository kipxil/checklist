<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['code' => 'DEV',  'name' => 'Developer', 'work_at' => '-'],
            ['code' => 'HTL',  'name' => 'Hotel',      'work_at' => 'Vasa'],
            ['code' => 'RST',  'name' => 'Restaurant', 'work_at' => '209 Dining'],
        ];

        foreach ($rows as $r) {
            Department::updateOrCreate(
                ['code' => $r['code']],
                ['name' => $r['name'], 'work_at' => $r['work_at']]
            );
        }
    }
}
