<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Upselling;
use App\Models\Beverage;

class UpsellingBeverageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $upsellings = [
            'Mushroom Soup',
            'Iga Bakar',
            'Tex Mex',
        ];
        foreach ($upsellings as $name) {
            Upselling::firstOrCreate(['name' => $name]);
        }

        $beverages = [
            'Bacha 4 Flavours',
            'Promo Wine by Glass B1G1 (All Hours Wine)',
            'Upselling Mocktail of the Month',
        ];
        foreach ($beverages as $name) {
            Beverage::firstOrCreate(['name' => $name]);
        }
    }
}
