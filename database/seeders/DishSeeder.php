<?php

namespace Database\Seeders;

use App\Models\Dish;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('ja_JP');

        for ($i = 1; $i <= 20; $i++) {
            Dish::create([
                'name' => $faker->randomElement(['麻婆豆腐', '寿司', '天ぷら', 'お好み焼き', '鍋', 'そば', '刺身', '焼肉', 'カレー', 'シチュー', '肉じゃが', 'うどん', 'ラーメン', 'ステーキ']),
                'genre_id' => '1',
                'icon' => 'mdi-food-outline',
                'reference_url' => $faker->url,
                'description' => $faker->sentence,
            ]);
        }
    }
}
