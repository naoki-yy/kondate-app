<?php

namespace App\Http\Controllers;

use App\Services\CurrentMenuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CurrentMenuController extends Controller
{
    protected $currentMenuService;

    public function __construct(CurrentMenuService $currentMenuService)
    {
        $this->currentMenuService = $currentMenuService;
    }

    /**
     * 今週の献立を取得
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $currentMenus = $this->currentMenuService->getCurrentMenus();
        return response()->json($currentMenus);
    }

    // /**
    //  * 今週の献立を登録
    //  *
    //  * @param Request $request
    //  * @return JsonResponse
    //  */
    // public function store(MenuRequest $request): JsonResponse
    // {

    //     $this->menuService->createMenu($request->all());
    //     return response()->json();
    // }

    // /**
    //  * 献立一覧ページ：献立更新処理
    //  *
    //  * @param MenuRequest $request
    //  * @param Menu $menu
    //  * @return JsonResponse
    //  */
    // public function update(MenuRequest $request, Menu $menu): JsonResponse
    // {
    //     $updatedMenu = $this->menuService->updateMenu($menu, $request->all());
    //     return response()->json(['menu' => $updatedMenu], 200);
    // }

    // /**
    //  * 献立一覧ページ：献立削除
    //  *
    //  * @param Menu $menu
    //  * @return JsonResponse
    //  */
    // public function destroy(Menu $menu): JsonResponse
    // {
    //     $this->menuService->deleteMenu($menu);
    //     return response()->json(200);
    // }
}
