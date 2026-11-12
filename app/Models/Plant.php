<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plant extends Model
{
    use HasFactory;

    // 追加：この植物に紐づく作業ログを取得（1対多・新しい順）
    public function careLogs(): HasMany
    {
        return $this->hasMany(CareLog::class)->orderBy('logged_at', 'desc');
    }

    // 保存を許可するカラム名を配列で指定
    protected $fillable = [
        'name',
        'nickname',
        'location',
    ];
}
