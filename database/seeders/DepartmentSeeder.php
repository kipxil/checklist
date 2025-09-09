<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['code' => 'DEV',  'name' => 'Developer'],
            ['code' => 'HTL',  'name' => 'Hotel'],
            ['code' => 'RST',  'name' => 'Restaurant'],
        ];

        foreach ($rows as $r) {
            Department::updateOrCreate(
                ['code' => $r['code']],
                ['name' => $r['name']]
            );
        }
    }
}
