<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plant;
use Illuminate\Support\Facades\Http;

class PlantController extends Controller
{
    /**
     * 植物一覧の表示
     */
    public function index(Request $request)
    {
        $selectedLocation = $request->query('location');
        $locations = Plant::whereNotNull('location')->where('location', '!=', '')->distinct()->pluck('location');

        $query = Plant::with(['careLogs' => function($q) {
            $q->latest('logged_at');
        }]);

        if ($selectedLocation) {
            $query->where('location', $selectedLocation);
        }
        $plants = $query->latest()->get();

        // --- ここから天気取得ロジック ---
        // 横浜の座標（緯度・経度）でOpen-Meteoから現在の天気を取得
        $weatherText = '取得中...';
        try {
            $response = Http::get('https://api.open-meteo.com/v1/forecast', [
                'latitude' => 35.4437,
                'longitude' => 139.6380,
                'current' => 'weather_code,temperature_2m',
                'timezone' => 'Asia/Tokyo'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $wmoCode = $data['current']['weather_code'] ?? 0;
                $temp = $data['current']['temperature_2m'] ?? '';
                
                // WMO天気コードを簡単な絵文字・日本語に変換
                $weatherText = $this->convertWmoCodeToText($wmoCode) . ($temp !== '' ? " {$temp}℃" : '');
            }
        } catch (\Exception $e) {
            $weatherText = '☀️ 晴れ'; // 失敗したときのフォールバック
        }
        // ----------------------------

        return view('plants.index', compact('plants', 'locations', 'selectedLocation', 'weatherText'));
    }

    /**
     * Open-MeteoのWMO天気コードを日本語と絵文字に変換するヘルパー
     */
    private function convertWmoCodeToText(int $code): string
    {
        return match (true) {
            $code === 0 => '☀️ 晴れ',
            in_array($code, [1, 2]) => '🌤 晴れ時々曇り',
            $code === 3 => '☁️ 曇り',
            in_array($code, [51, 53, 55, 61, 63, 65]) => '🌧 雨',
            in_array($code, [71, 73, 75]) => '❄️ 雪',
            in_array($code, [95, 96, 99]) => '⚡ 雷雨',
            default => '☁️ 曇り',
        };
    }

    /**
     * 新規登録フォームを表示
     */
    public function create()
    {
        return view('plants.create');
    }

    /**
     * データをデータベースに保存
     */
    public function store(Request $request)
    {
        // 1. フォームから送られてきたデータのバリデーション（入力チェック）
        $validated = $request->validate([
            'name' => 'required|max:255',
            'nickname' => 'nullable|max:255',
            'location' => 'nullable|max:255',
        ]);

        // 2. データベースに保存
        Plant::create($validated);

        // 3. 一覧ページにリダイレクト（戻る）して完了を伝える
        return redirect()->route('plants.index')->with('success', '新しい植物を追加しました！');
    }

    /**
     * 植物の詳細ページを表示
     */
    public function show(Plant $plant)
    {
        return view('plants.show', compact('plant'));
    }

    /**
     * 植物の編集フォームを表示する
     */
    public function edit(Plant $plant)
    {
        return view('plants.edit', compact('plant'));
    }

    /**
     * 植物のデータを更新する
     */
    public function update(Request $request, Plant $plant)
    {
        // 1. バリデーション（入力チェック）
        $validated = $request->validate([
            'name' => 'required|max:255',
            'nickname' => 'nullable|max:255',
            'location' => 'nullable|max:255',
        ]);

        // 2. データを更新して保存
        $plant->update($validated);

        // 3. 詳細ページ（または一覧）にリダイレクトして完了メッセージを添える
        return redirect()->route('plants.show', $plant)->with('success', '植物の情報を更新しました！');
    }

    /**
     * 植物のデータを削除する
     */
    public function destroy(Plant $plant)
    {
        // 1. データベースから削除
        $plant->delete();

        // 2. 一覧ページにリダイレクトして完了メッセージを伝える
        return redirect()->route('plants.index')->with('success', '植物を削除しました。');
    }
}
