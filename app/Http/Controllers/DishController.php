<?php

namespace App\Http\Controllers;

use App\Http\Requests\DishRequest;
use App\Services\DishService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DishController extends Controller
{
    protected $dishService;

    public function __construct(DishService $dishService)
    {
        $this->dishService = $dishService;
    }

    /**
     * 料理一覧の取得
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $dishes = $this->dishService->getAllDishes();
        return response()->json($dishes);
    }

    /**
     * 料理を登録
     *
     * @param DishRequest $request
     * @return JsonResponse
     */
    public function store(DishRequest $request): JsonResponse
    {

        $this->dishService->createDish($request->all());
        return response()->json();
    }

    // /**
    //  * 献立一覧ページ：献立更新処理
    //  *
    //  * @param MenuRequest $request
    //  * @param Menu $menu
    //  * @return JsonResponse
    //  */
    // public function update(MenuRequest $request, Menu $menu): JsonResponse
    // {
    //     $updatedMenu = $this->dishService->updateMenu($menu, $request->all());
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
    //     $this->dishService->deleteMenu($menu);
    //     return response()->json(200);
    // }

}
