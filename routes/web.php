<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\CareLogController;

Route::get('/', function () {
    return view('welcome');
});

// 植物一覧ページへのルート
Route::get('/plants', [PlantController::class, 'index'])->name('plants.index');

// 植物新規登録フォーム画面
Route::get('/plants/create', [PlantController::class, 'create'])->name('plants.create');

// 植物データの保存処理
Route::post('/plants', [PlantController::class, 'store'])->name('plants.store');

// 植物の詳細ページ
Route::get('/plants/{plant}', [PlantController::class, 'show'])->name('plants.show');

// 植物の編集フォーム画面
Route::get('/plants/{plant}/edit', [PlantController::class, 'edit'])->name('plants.edit');

// 植物データの更新処理
Route::put('/plants/{plant}', [PlantController::class, 'update'])->name('plants.update');

// 植物データの削除処理
Route::delete('/plants/{plant}', [PlantController::class, 'destroy'])->name('plants.destroy');

// ケアログ登録用ルート
Route::post('/plants/{plant}/care-logs', [CareLogController::class, 'store'])->name('care-logs.store');

// クイック水やり用ルート
Route::post('/plants/{plant}/quick-water', [CareLogController::class, 'quickWater'])
    ->name('plants.quick-water');