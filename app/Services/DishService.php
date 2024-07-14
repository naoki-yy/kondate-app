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

    // public function updateMenu(Menu $menu, array $data)
    // {
    //     $menu->fill($data);
    //     $menu->last_used_date = now();
    //     $menu->save();

    //     return $menu;
    // }

    // public function deleteMenu(Menu $menu)
    // {
    //     $menu->delete();
    // }
}
