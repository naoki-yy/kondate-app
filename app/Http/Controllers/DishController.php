<?php

namespace App\Http\Controllers;

use App\Http\Requests\DishRequest;
use App\Models\Dish;
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

    /**
     * 料理IDを取得
     *
     * @param string $name
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDishIdByName($name): JsonResponse
    {
        $dishId = $this->dishService->getDishIdByName($name);
        return response()->json(['id' => $dishId]);
    }

    /**
     * 料理詳細情報の取得
     *
     * @param Dish $dish
     * @return void
     */
    public function show(Dish $dish)
    {
        $dish_detail = $this->dishService->showDish($dish);
        return response()->json($dish_detail);
    }



    /**
     * 料理更新処理
     *
     * @param dishRequest $request
     * @param dish $dish
     * @return JsonResponse
     */
    public function update(dishRequest $request, Dish $dish): JsonResponse
    {
        $updatedDish = $this->dishService->updateDish($dish, $request->all());
        return response()->json(['dish' => $updatedDish], 200);
    }

    /**
     * 料理削除
     *
     * @param Dish $dish
     * @return JsonResponse
     */
    public function destroy(Dish $dish): JsonResponse
    {
        $this->dishService->deleteDIsh($dish);
        return response()->json(200);
    }
}
