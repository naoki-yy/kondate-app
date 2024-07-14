<?php

namespace Database\Seeders;

use App\Models\Dish;
use App\Models\Material;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('ja_JP');

        // 全てのDishに対して材料を作成する
        $dishes = Dish::all();
        foreach ($dishes as $dish) {
            for ($i = 1; $i <= 5; $i++) {
                Material::create([
                    'dish_id' => $dish->id,
                    'material' => $faker->word,
                    'amount' => $faker->randomNumber(2) . 'g',
                ]);
            }
        }
    }
}
