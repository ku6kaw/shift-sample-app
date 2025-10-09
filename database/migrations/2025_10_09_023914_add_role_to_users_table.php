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
        Schema::table('users', function (Blueprint $table) {
            // roleカラムを追加。デフォルト値は2（一般スタッフ）とする。
            // nameカラムの後に作成する。
            $table->tinyInteger('role')->default(2)->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // マイグレーションをロールバックした際にroleカラムを削除する
            $table->dropColumn('role');
        });
    }
};
