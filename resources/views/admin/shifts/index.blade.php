<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                週間シフト管理
            </h2>
            <a href="{{ route('admin.shifts.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">
                シフト作成
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <a href="?start_date={{ $prevWeek }}" class="px-4 py-2 bg-gray-200 rounded">&lt; 前の週</a>
                        <span class="font-bold text-lg">{{ $dates[0]->format('Y年n月j日') }} - {{ $dates[6]->format('n月j日') }}</span>
                        <a href="?start_date={{ $nextWeek }}" class="px-4 py-2 bg-gray-200 rounded">次の週 &gt;</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase">スタッフ</th>
                                    @foreach ($dates as $date)
                                        <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                            {{ $date->format('n/j') }} <br> ({{ mb_substr($date->dayName, 0, 1) }})
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($staffs as $staff)
                                    <tr>
                                        <td class="px-2 py-4 whitespace-nowrap font-medium">{{ $staff->name }}</td>
                                        @foreach ($dates as $date)
                                            <td class="border-l border-gray-200">
                                                @foreach ($shifts->where('user_id', $staff->id)->where('work_date', $date->format('Y-m-d')) as $shift)
                                                    <a href="{{ route('admin.shifts.edit', $shift) }}" class="block text-center text-sm bg-blue-200 rounded-md p-1 m-1 hover:bg-blue-300">
                                                        {{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}
                                                    </a>
                                                @endforeach
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
