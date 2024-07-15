<?php

use App\Http\Controllers\CurrentMenuController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MenuController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');
Route::post('/menus/register', [MenuController::class, 'store'])->name('menus.store');
Route::put('/menus/{menu}', [MenuController::class, 'update'])->name('menus.edit');
Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->name('menus.delete');

Route::get('/current_menus', [CurrentMenuController::class, 'index'])->name('current_menus.index');

Route::get('/dishes', [DishController::class, 'index'])->name('dishes.index');
Route::get('/dish-id/{name}', [DishController::class, 'getDishIdByName'])->name('dish.getId');;
Route::get('/dish/{dish}', [DishController::class, 'show'])->name('dish.show');
Route::post('/dishes/register', [DishController::class, 'store'])->name('dishes.store');
Route::put('/dish/{dish}', [DishController::class, 'update'])->name('dishes.edit');
Route::delete('/dish/{dish}', [DishController::class, 'destroy'])->name('dishes.delete');

Route::get('/genres', [GenreController::class, 'index'])->name('genre.index');
Route::post('/genre/register', [GenreController::class, 'store'])->name('genre.store');
Route::put('/genre/{genre}', [GenreController::class, 'update'])->name('genre.edit');
Route::delete('/genre/{genre}', [GenreController::class, 'destroy'])->name('genre.destroy');


Route::post('/material/register', [MaterialController::class, 'store'])->name('material.store');
Route::put('/material/{material}', [MaterialController::class, 'update'])->name('material.edit');
Route::delete('/material/{material}', [MaterialController::class, 'destroy'])->name('material.destroy');
