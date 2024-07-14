<?php

namespace App\Services;

use App\Models\currentWeekMenu;
use App\Models\Menu;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class MenuService
{
    /**
     * menusテーブルからデータ取得
     *
     * @return Collection
     */
    public function getAllMenus(): Collection
    {
        return Menu::orderBy('id', 'desc')->get();
    }

    /**
     * 今週の献立登録
     *
     * @param Array $data
     * @return void
     */
    public function createMenu(array $data): void
    {
        //献立テーブルに保存（献立一覧に表示)
        $menu = new Menu();
        $menu->sunday = $data['sunday'];
        $menu->monday = $data['monday'];
        $menu->tuesday = $data['tuesday'];
        $menu->wednesday = $data['wednesday'];
        $menu->thursday = $data['thursday'];
        $menu->friday = $data['friday'];
        $menu->saturday = $data['saturday'];
        $menu->totalAmount = $data['totalAmount'];
        $menu->last_used_date = Carbon::now();

        $menu->save();

        //今週の献立テーブルに保存
        $current_week_menu = new currentWeekMenu();
        $current_week_menu->sunday = $data['sunday'];
        $current_week_menu->monday = $data['monday'];
        $current_week_menu->tuesday = $data['tuesday'];
        $current_week_menu->wednesday = $data['wednesday'];
        $current_week_menu->thursday = $data['thursday'];
        $current_week_menu->friday = $data['friday'];
        $current_week_menu->saturday = $data['saturday'];
        $current_week_menu->totalAmount = $data['totalAmount'];

        $current_week_menu->save();
    }

    /**
     * 献立の更新
     *
     * @param Menu $menu
     * @param array $data
     * @return Menu
     */
    public function updateMenu(Menu $menu, array $data): Menu
    {
        $menu->fill($data);
        $menu->last_used_date = now();
        $menu->save();

        return $menu;
    }

    /**
     * 献立の削除
     *
     * @param Menu $menu
     * @return void
     */
    public function deleteMenu(Menu $menu): void
    {
        $menu->delete();
    }
}
