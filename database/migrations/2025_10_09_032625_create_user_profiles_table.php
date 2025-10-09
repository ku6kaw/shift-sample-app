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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            // usersテーブルのidと1対1で関連付け
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('preferred_days')->nullable(); // 希望曜日
            $table->time('preferred_start_time')->nullable(); // 希望開始時間
            $table->time('preferred_end_time')->nullable(); // 希望終了時間
            $table->text('memo')->nullable(); // 備考
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
