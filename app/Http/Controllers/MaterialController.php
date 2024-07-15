<?php

namespace App\Http\Controllers;

use App\Http\Requests\DishRequest;
use App\Http\Requests\MaterialRequest;
use App\Models\Dish;
use App\Models\Material;
use App\Services\MaterialService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    protected $materialService;

    public function __construct(MaterialService $materialService)
    {
        $this->materialService = $materialService;
    }

    // /**
    //  * 材料一覧の取得
    //  *
    //  * @return JsonResponse
    //  */
    // public function index(): JsonResponse
    // {
    //     $dishes = $this->materialService->getAllDishes();
    //     return response()->json($dishes);
    // }

    /**
     * 材料を登録
     *
     * @param DishRequest $request
     * @return JsonResponse
     */
    public function store(MaterialRequest $request): JsonResponse
    {

        $this->materialService->createMaterial($request->all());
        return response()->json();
    }

    // /**
    //  * 材料詳細情報の取得
    //  *
    //  * @param Dish $dish
    //  * @return void
    //  */
    // public function show(Dish $dish)
    // {
    //     $dish_detail = $this->materialService->showDish($dish);
    //     return response()->json($dish_detail);
    // }



    /**
     * 材料更新処理
     *
     * @param MaterialRequest $request
     * @param Material $material
     * @return JsonResponse
     */
    public function update(MaterialRequest $request, Material $material): JsonResponse
    {
        $updatedmaterial = $this->materialService->updateMaterial($material, $request->all());
        return response()->json(['material' => $updatedmaterial], 200);
    }

    /**
     * 材料削除
     *
     * @param Menu $menu
     * @return JsonResponse
     */
    public function destroy(Material $material): JsonResponse
    {
        $this->materialService->deleteMaterial($material);
        return response()->json(200);
    }
}
