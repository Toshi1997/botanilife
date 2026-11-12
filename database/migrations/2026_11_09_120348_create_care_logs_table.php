<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('care_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plant_id')->constrained()->onDelete('cascade'); // 植物ID（外部キー）
            $table->timestamp('logged_at');                                    // 作業日時（時間まで記録）
            $table->string('care_type');                                       // 作業種別
            $table->string('image_path')->nullable();                          // 画像パス
            $table->text('memo')->nullable();                                  // 作業メモ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('care_logs');
    }
};
