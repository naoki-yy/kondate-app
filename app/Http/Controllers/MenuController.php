<?php

namespace App\Http\Controllers;

use App\Http\Requests\MenuRequest;
use App\Models\Menu;
use App\Services\MenuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    protected $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    /**
     * 献立一覧の取得
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $menus = $this->menuService->getAllMenus();
        return response()->json($menus);
    }

    /**
     * 今週の献立を登録
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(MenuRequest $request): JsonResponse
    {

        $this->menuService->createMenu($request->all());
        return response()->json();
    }

    /**
     * 献立一覧ページ：献立更新処理
     *
     * @param MenuRequest $request
     * @param Menu $menu
     * @return JsonResponse
     */
    public function update(MenuRequest $request, Menu $menu): JsonResponse
    {
        $updatedMenu = $this->menuService->updateMenu($menu, $request->all());
        return response()->json(['menu' => $updatedMenu], 200);
    }

    /**
     * 献立一覧ページ：献立削除
     *
     * @param Menu $menu
     * @return JsonResponse
     */
    public function destroy(Menu $menu): JsonResponse
    {
        $this->menuService->deleteMenu($menu);
        return response()->json(200);
    }
}
