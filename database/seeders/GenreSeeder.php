<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('ja_JP');

        $genres = ['和食', '洋食', '中華', 'フレンチ', 'イタリアン', '丼もの'];

        for ($i = 0; $i < 5; $i++) {
            Genre::create([
                'name' => $genres[$i],
            ]);
        }
    }
}
