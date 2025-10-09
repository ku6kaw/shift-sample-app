<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            ダッシュボード
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <div class="md:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-1">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h3 class="font-semibold text-lg mb-4">基本シフト</h3>
                            <div class="space-y-2">
                                @foreach ($days as $dayOfWeek => $dayName)
                                    <div class="flex justify-between border-b pb-1">
                                        <span class="font-medium">{{ $dayName }}曜日</span>
                                        <span>
                                            @if(isset($preferredSchedules[$dayOfWeek]))
                                                {{ \Carbon\Carbon::parse($preferredSchedules[$dayOfWeek]->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($preferredSchedules[$dayOfWeek]->end_time)->format('H:i') }}
                                            @else
                                                <span class="text-gray-400">休み</span>
                                            @endif
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/index.global.min.js'></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'ja',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                // Laravelから渡されたイベントデータ
                events: {!! $events !!},

                selectable: true, // 日付を選択可能にする

                // イベント（シフト）がクリックされた時の処理
                eventClick: function(info) {
                    // 管理者(role=1)の場合のみ動作
                    @if(Auth::user()->role == 1)
                        // クリックされたイベントのIDを取得
                        let shiftId = info.event.id;
                        // 編集ページへ画面遷移
                        window.location.href = `/admin/shifts/${shiftId}/edit`;
                    @endif
                }
            });
            calendar.render();
        });
    </script>
    @endpush
</x-app-layout>
