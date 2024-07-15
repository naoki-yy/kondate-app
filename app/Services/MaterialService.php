<?php

namespace App\Services;

use App\Models\Material;
use Illuminate\Database\Eloquent\Collection;

class MaterialService
{

    /**
     * 材料追加
     *
     * @param Array $data
     * @return void
     */
    public function createMaterial(array $data): void
    {
        //材料テーブルに保存
        $material = new Material();
        $material->fill($data);
        $material->save();
    }


    // /**
    //  * 料理詳細情報を取得
    //  *
    //  * @param Dish $dish
    //  * @return void
    //  */
    // public function showDish(Dish $dish)
    // {
    //     return $dish->load('materials');
    // }

    /**
     * 材料更新
     *
     * @param Material $material
     * @param array $data
     * @return void
     */
    public function updateMaterial(Material $material, array $data)
    {
        $material->fill($data);
        $material->save();

        return $material;
    }

    /**
     * 材料削除
     *
     * @param Material $material
     * @return void
     */
    public function deleteMaterial(Material $material)
    {
        $material->delete();
    }
}
