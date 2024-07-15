<?php

namespace App\Services;

use App\Models\Dish;
use Illuminate\Database\Eloquent\Collection;

class DishService
{
    /**
     * dishesテーブルからデータ取得
     *
     * @return Collection
     */
    public function getAllDishes(): Collection
    {
        return Dish::with('genre')->orderBy('id', 'desc')->get();
    }

    /**
     * 料理新規登録
     *
     * @param Array $data
     * @return void
     */
    public function createDish(array $data): void
    {
        //料理テーブルに保存（料理一覧に表示)
        $menu = new Dish();
        $menu->fill($data);
        $menu->save();
    }

    /**
     * 料理名から料理IDを取得
     *
     * @param string $name
     * @return int|null
     */
    public function getDishIdByName(string $name): ?int
    {
        $dish = Dish::where('name', $name)->first();
        return $dish ? $dish->id : null;
    }

    /**
     * 料理詳細情報を取得
     *
     * @param Dish $dish
     * @return void
     */
    public function showDish(Dish $dish)
    {
        return $dish->load('materials');
    }

    public function updateDish(Dish $dish, array $data)
    {
        $dish->fill($data);
        $dish->save();

        return $dish;
    }

    /**
     * 料理削除
     *
     * @param Dish $dish
     * @return void
     */
    public function deleteDish(Dish $dish): void
    {
        $dish->delete();
    }
}
