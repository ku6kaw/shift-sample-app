<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * ユーザー（スタッフ）一覧画面を表示
     */
    public function index()
    {
        // 管理者(role=1)以外のユーザーを取得
        $users = User::where('role', '!=', 1)->get();
        return view('admin.users.index', ['users' => $users]);
    }

    /**
     * ユーザー作成フォームを表示
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * ユーザーをデータベースに保存
     */
    public function store(Request $request)
    {
        // 入力値のバリデーション
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'integer'],
        ]);

        // ユーザーを作成
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // パスワードは必ずハッシュ化
            'role' => $request->role,
        ]);

        // 成功したら一覧画面に戻る
        return redirect()->route('admin.users.index')->with('success', '新しいユーザーを登録しました。');
    }
}
