<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    /**
     * シフト作成フォームを表示
     */
    public function create()
    {
        // スタッフ（role=2）の一覧を取得
        $staffs = User::where('role', 2)->get();
        return view('admin.shifts.create', ['staffs' => $staffs]);
    }

    /**
     * シフトをDBに保存
     */
    public function store(Request $request)
    {
        // 入力値のバリデーション
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'work_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        // Shiftモデルのインスタンスを作成してデータを保存
        $shift = new Shift();
        $shift->user_id = $request->user_id;
        $shift->work_date = $request->work_date;
        $shift->start_time = $request->start_time;
        $shift->end_time = $request->end_time;
        $shift->save();

        // 成功メッセージと共に作成画面に戻る
        return redirect()->route('admin.shifts.create')->with('success', 'シフトを登録しました。');
    }
}
