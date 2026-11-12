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
        Schema::create('plants', function (Blueprint $table) {
            $table->id();
            $table->string('name');                             // 品種名・植物名
            $table->string('nickname')->nullable();             // ニックネーム
            $table->string('location')->nullable();             // 置き場所
            $table->date('acquired_date')->nullable();          // お迎え日
            $table->text('memo')->nullable();                   // メモ
            $table->string('status')->default('Growing');       // ステータス（Growing, Harvested, Separated等）
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plants');
    }
};
