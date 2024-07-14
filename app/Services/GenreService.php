<?php

namespace App\Services;

use App\Models\Dish;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Collection;

class GenreService
{
    /**
     * dishesテーブルからデータ取得
     *
     * @return Collection
     */
    public function getAllGenres(): Collection
    {
        return Genre::orderBy('id', 'asc')->get();
    }

    /**
     * ジャンルの新規登録
     *
     * @param Array $data
     * @return void
     */
    public function createGenre(array $data): string
    {
        //料理テーブルに保存（料理一覧に表示)
        $genre = new Genre();
        $genre->fill($data);
        $genre->save();

        return $data['name'];
    }

    /**
     * ジャンルの更新
     *
     * @param Genre $genre
     * @param array $data
     * @return Genre
     */
    public function updateGenre(Genre $genre, array $data): Genre
    {
        $genre->fill($data);
        $genre->save();

        return $genre;
    }

    /**
     * ジャンルの削除
     *
     * @param Genre $genre
     * @return void
     */
    public function deleteGenre(Genre $genre): void
    {
        $genre->delete();
    }
}
