<?php

namespace App\Models;

use App\Enums\CareType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class CareLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'plant_id',
        'care_type',
        'memo',
        'image_path',
        'logged_at',
    ];

    protected $casts = [
        'care_type' => CareType::class,
        'logged_at' => 'datetime',
    ];

    // Plant モデルとのリレーション（多対1）
    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }

    /**
     * モデルの削除時に紐づく画像ファイルも自動削除する
     */
    protected static function booted(): void
    {
        static::deleting(function (CareLog $careLog) {
            if ($careLog->image_path && Storage::disk('public')->exists($careLog->image_path)) {
                Storage::disk('public')->delete($careLog->image_path);
            }
        });
    }
}
