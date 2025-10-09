<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shift;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $events = []; // カレンダーに渡すための空の配列を準備

        if ($user->role == 1) { // 管理者の場合
            // 全員のシフトを取得
            $shifts = Shift::with('user')->get();
            foreach ($shifts as $shift) {
                $events[] = [
                    'title' => $shift->user->name, // イベントのタイトルにスタッフ名を表示
                    'start' => $shift->work_date . 'T' . $shift->start_time, // 開始日時
                    'end' => $shift->work_date . 'T' . $shift->end_time,   // 終了日時
                ];
            }
        } else { // 一般スタッフの場合
            // 自分のシフトだけを取得
            $shifts = $user->shifts()->get();
            foreach ($shifts as $shift) {
                $events[] = [
                    'title' => '勤務', // タイトルは「勤務」
                    'start' => $shift->work_date . 'T' . $shift->start_time,
                    'end' => $shift->work_date . 'T' . $shift->end_time,
                ];
            }
        }

        // 加工したシフト情報をビューに渡す
        return view('dashboard', ['events' => json_encode($events)]);
    }
}
