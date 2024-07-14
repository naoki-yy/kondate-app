<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('ja_JP');

        for ($i = 1; $i <= 20; $i++) {
            Menu::create([
                'sunday' => $faker->randomElement(['麻婆豆腐', '寿司', '天ぷら', 'お好み焼き', '鍋', 'そば', '刺身', '焼肉', 'カレー', 'シチュー', '肉じゃが', 'うどん', 'ラーメン', 'ステーキ']),
                'monday' => $faker->randomElement(['麻婆豆腐', '寿司', '天ぷら', 'お好み焼き', '鍋', 'そば', '刺身', '焼肉', 'カレー', 'シチュー', '肉じゃが', 'うどん', 'ラーメン', 'ステーキ']),
                'tuesday' => $faker->randomElement(['麻婆豆腐', '寿司', '天ぷら', 'お好み焼き', '鍋', 'そば', '刺身', '焼肉', 'カレー', 'シチュー', '肉じゃが', 'うどん', 'ラーメン', 'ステーキ']),
                'wednesday' => $faker->randomElement(['麻婆豆腐', '寿司', '天ぷら', 'お好み焼き', '鍋', 'そば', '刺身', '焼肉', 'カレー', 'シチュー', '肉じゃが', 'うどん', 'ラーメン', 'ステーキ']),
                'thursday' => $faker->randomElement(['麻婆豆腐', '寿司', '天ぷら', 'お好み焼き', '鍋', 'そば', '刺身', '焼肉', 'カレー', 'シチュー', '肉じゃが', 'うどん', 'ラーメン', 'ステーキ']),
                'friday' => $faker->randomElement(['麻婆豆腐', '寿司', '天ぷら', 'お好み焼き', '鍋', 'そば', '刺身', '焼肉', 'カレー', 'シチュー', '肉じゃが', 'うどん', 'ラーメン', 'ステーキ']),
                'saturday' => $faker->randomElement(['麻婆豆腐', '寿司', '天ぷら', 'お好み焼き', '鍋', 'そば', '刺身', '焼肉', 'カレー', 'シチュー', '肉じゃが', 'うどん', 'ラーメン', 'ステーキ']),
                'totalAmount' => $faker->numberBetween(3000, 10000),
                'last_used_date' => $faker->dateTimeBetween('-1 years', 'now')->format('y-m-d'),
            ]);
        }
    }
}
