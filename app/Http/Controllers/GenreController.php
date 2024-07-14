<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenreRequest;
use App\Models\Genre;
use App\Services\GenreService;
use Illuminate\Http\JsonResponse;

class GenreController extends Controller
{
    protected $genreService;

    public function __construct(GenreService $genreService)
    {
        $this->genreService = $genreService;
    }

    /**
     * ジャンルの取得
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $genres = $this->genreService->getAllGenres();
        return response()->json($genres);
    }

    /**
     * ジャンルを登録
     *
     * @param GenreRequest $request
     * @return JsonResponse
     */
    public function store(GenreRequest $request): JsonResponse
    {

        $genre_name = $this->genreService->createGenre($request->all());
        return response()->json($genre_name);
    }

    /**
     * ジャンルの更新処理
     *
     * @param GenreRequest $request
     * @param Genre $genre
     * @return JsonResponse
     */
    public function update(GenreRequest $request, Genre $genre): JsonResponse
    {
        $updatedGenre = $this->genreService->updateGenre($genre, $request->all());
        return response()->json(['genre' => $updatedGenre], 200);
    }

    /**
     * ジャンル削除
     *
     * @param Menu $menu
     * @return JsonResponse
     */
    public function destroy(Genre $genre): JsonResponse
    {
        $this->genreService->deleteGenre($genre);
        return response()->json(200);
    }
}
