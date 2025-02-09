<?php

namespace Database\Seeders;

use App\Models\Car\CarBrand;
use App\Models\Car\CarModel;
use Illuminate\Database\Seeder;

class CarModelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $models = [
            'Toyota' => ['Camry', 'Corolla', 'RAV4'],
            'BMW' => ['X5', 'X3', 'X7'],
            'Mercedes-Benz' => ['E350', 'E300', 'E200']
        ];

        $carBands = CarBrand::query()->get();
        foreach ($models as $brand => $modelList) {
            $carBrand = $carBands->where('title', $brand)->first();
            if (!$carBrand) {
                continue;
            }

            foreach ($modelList as $model) {
                CarModel::query()->updateOrCreate([
                    'title' => $model,
                    'car_brand_id' => $carBrand->id
             ], []);
            }
        }

    }
}
