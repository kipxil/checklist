<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DepartmentSeeder::class,
            PositionSeeder::class,
            AdminUserSeeder::class, // user dibuat setelah dept & position ada
            UpsellingBeverageSeeder::class,
        ]);
    }
}
