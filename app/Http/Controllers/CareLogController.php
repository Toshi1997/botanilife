<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Models\CareLog;
use Illuminate\Http\Request;
use App\Enums\CareType;
use Illuminate\Validation\Rules\Enum;

class CareLogController extends Controller
{
    public function store(Request $request, Plant $plant)
    {
        $validated = $request->validate([
            'care_type' => ['required', new Enum(CareType::class)],
            'logged_at' => ['required', 'date'],
            'memo' => ['nullable', 'string', 'max:1000'],
        ]);

        // 画像がアップロードされている場合の処理
        if ($request->hasFile('image')) {
            // storage/app/public/care_logs に保存し、相対パスを取得
            $path = $request->file('image')->store('care_logs', 'public');
            $validated['image_path'] = $path;
        }

        // 紐づく植物IDを付与して保存
        $plant->careLogs()->create($validated);

        return redirect()->route('plants.show', $plant)
            ->with('success', '作業ログを記録しました！');
    }

    /**
     * 一覧画面などからワンクリックで水やりを記録する
     */
    public function quickWater(Plant $plant)
    {
        $plant->careLogs()->create([
            'care_type' => CareType::Watering->value, // Enumの「水やり」の値
            'logged_at' => now(),
            'memo' => 'クイック水やり',
        ]);

        return back()->with('success', "{$plant->name} の水やりを記録しました！");
    }
}
