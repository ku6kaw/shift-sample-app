<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            シフト編集
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <form id="editForm" method="POST" action="{{ route('admin.shifts.update', $shift) }}">
                        @csrf
                        @method('PATCH')

                        <div class="mt-4">
                            <label for="user_id" class="block font-medium text-sm text-gray-700">スタッフ</label>
                            <select id="user_id" name="user_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                @foreach ($staffs as $staff)
                                    <option value="{{ $staff->id }}" @if($staff->id === $shift->user_id) selected @endif>{{ $staff->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-4">
                            <label for="work_date" class="block font-medium text-sm text-gray-700">勤務日</label>
                            <input id="work_date" class="block mt-1 w-full" type="date" name="work_date" value="{{ $shift->work_date }}" required />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="mt-4">
                                <label for="start_time" class="block font-medium text-sm text-gray-700">開始時刻</label>
                                <input id="start_time" class="block mt-1 w-full" type="time" name="start_time" value="{{ $shift->start_time }}" required />
                            </div>

                            <div class="mt-4">
                                <label for="end_time" class="block font-medium text-sm text-gray-700">終了時刻</label>
                                <input id="end_time" class="block mt-1 w-full" type="time" name="end_time" value="{{ $shift->end_time }}" required />
                            </div>
                        </div>
                    </form> <div class="flex items-center justify-between mt-4">
                        <form method="POST" action="{{ route('admin.shifts.destroy', $shift) }}" onsubmit="return confirm('本当に削除しますか？');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">
                                このシフトを削除する
                            </button>
                        </form>

                        <button type="submit" form="editForm" class="ml-4 inline-flex items-center px-4 py-2 bg-gray-800 ...">
                            更新する
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
