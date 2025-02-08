<?php

namespace Database\Seeders;

use App\Models\CarBrand;
use Illuminate\Database\Seeder;

class CarBrandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $carBrands = [
            [
                'title' => 'Mercedes-Benz'
            ],
            [
                'title' => 'BMW'
            ],
            [
                'title' => 'Toyota'
            ],
        ];
        foreach ($carBrands as $carBrand) {
            CarBrand::query()->updateOrCreate([
                'title' => $carBrand['title']
            ], $carBrand);
        }

    }
}
