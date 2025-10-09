<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * アプリケーションの初期データを投入する
     */
    public function run(): void
    {
        // 1. 管理者ユーザーを作成
        User::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 1, // 1: 管理者
        ]);

        // 2. 一般スタッフのテストユーザーを作成
        User::create([
            'name' => 'staff',
            'email' => 'staff@example.com',
            'password' => Hash::make('password'),
            'role' => 2, // 2: 一般スタッフ
        ]);
    }
}
