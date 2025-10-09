<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    /**
     * 週間シフト管理ページを表示
     */
    public function index(Request $request)
    {
        // クエリパラメータから週の開始日を取得。なければ今週の日曜日。
        $startOfWeek = $request->query('start_date') ? Carbon::parse($request->query('start_date')) : now()->startOfWeek();

        // 週の開始日（日曜）と終了日（土曜）を計算
        $startDate = $startOfWeek->copy();
        $endDate = $startOfWeek->copy()->endOfWeek();

        // 該当中の一週間の日付を配列に格納
        $dates = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dates[] = $date->copy();
        }

        // 全スタッフの情報を取得
        $staffs = User::where('role', 2)->orderBy('name')->get();

        // 該当週のシフト情報を取得
        $shifts = Shift::with('user')
            ->whereBetween('work_date', [$startDate, $endDate])
            ->get();

        return view('admin.shifts.index', [
            'staffs' => $staffs,
            'shifts' => $shifts,
            'dates' => $dates,
            'prevWeek' => $startDate->copy()->subWeek()->toDateString(), // 前の週の開始日
            'nextWeek' => $startDate->copy()->addWeek()->toDateString(), // 次の週の開始日
        ]);
    }

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

    /**
     * シフト編集フォームを表示
     */
    public function edit(Shift $shift)
    {
        // スタッフ一覧を取得
        $staffs = User::where('role', 2)->get();

        // 編集対象のシフト情報とスタッフ一覧をビューに渡す
        return view('admin.shifts.edit', [
            'shift' => $shift,
            'staffs' => $staffs,
        ]);
    }

    /**
     * シフトを更新する
     */
    public function update(Request $request, Shift $shift)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'work_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        $shift->user_id = $request->user_id;
        $shift->work_date = $request->work_date;
        $shift->start_time = $request->start_time;
        $shift->end_time = $request->end_time;
        $shift->save();

        // 成功したらダッシュボードに戻る
        return redirect()->route('dashboard')->with('success', 'シフトを更新しました。');
    }

    /**
     * シフトを削除する
     */
    public function destroy(Shift $shift)
    {
        $shift->delete();

        // 成功したらダッシュボードに戻る
        return redirect()->route('dashboard')->with('success', 'シフトを削除しました。');
    }
}

