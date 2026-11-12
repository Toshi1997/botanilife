<?php

namespace App\Enums;

enum CareType: string
{
    case Watering = 'watering';      // 水やり
    case Fertilizer = 'fertilizer';  // 肥料
    case Pruning = 'pruning';        // 剪定
    case Repotting = 'repotting';     // 植え替え
    case Photo = 'photo';            // 写真撮影のみ
    case Other = 'other';            // その他

    // 日本語ラベルを返すヘルパーメソッド
    public function label(): string
    {
        return match($this) {
            self::Watering => '水やり',
            self::Fertilizer => '肥料',
            self::Pruning => '剪定',
            self::Repotting => '植え替え',
            self::Photo => '写真撮影のみ',
            self::Other => 'その他',
        };
    }
}