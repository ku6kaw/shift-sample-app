<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * プロフィール編集画面を表示
     */
    public function edit()
    {
        $user = Auth::user();
        // ユーザーの希望スケジュールを取得し、曜日をキーにした連想配列に変換
        $preferredSchedules = $user->preferredSchedules->keyBy('day_of_week');

        // 曜日の配列を定義
        $days = ['日', '月', '火', '水', '木', '金', '土'];

        return view('profile.edit', [
            'user' => $user,
            'preferredSchedules' => $preferredSchedules,
            'days' => $days,
        ]);
    }

    /**
     * プロフィールを更新
     */
    public function update(Request $request)
    {
        // バリデーションルールを定義
        $request->validate([
            'schedules' => 'array',
            'schedules.*.start_time' => 'nullable|date_format:H:i',
            'schedules.*.end_time' => 'nullable|date_format:H:i|after_or_equal:schedules.*.start_time',
        ]);

        $user = Auth::user();

        // 送信された7日間のスケジュールをループ処理
        foreach ($request->schedules as $dayOfWeek => $times) {
            // 開始時間と終了時間の両方が入力されている場合のみ更新・作成
            if (isset($times['start_time']) && isset($times['end_time'])) {
                $user->preferredSchedules()->updateOrCreate(
                    [
                        'day_of_week' => $dayOfWeek, // 曜日
                    ],
                    [
                        'start_time' => $times['start_time'], // 開始時間
                        'end_time' => $times['end_time'],   // 終了時間
                    ]
                );
            } else {
                // 片方または両方が空の場合は、その曜日のデータを削除
                $user->preferredSchedules()->where('day_of_week', $dayOfWeek)->delete();
            }
        }

        return redirect()->route('profile.edit')->with('success', '勤務希望を更新しました。');
    }
}
