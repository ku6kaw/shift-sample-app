<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            勤務希望時間の編集
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="space-y-4">
                            @foreach ($days as $dayOfWeek => $dayName)
                                <div class="grid grid-cols-3 items-center gap-4 p-2 border rounded-md">
                                    <div class="font-bold">{{ $dayName }}曜日</div>

                                    <div>
                                        <label for="schedules_{{ $dayOfWeek }}_start_time" class="block text-sm font-medium text-gray-700">開始時間</label>
                                        <input id="schedules_{{ $dayOfWeek }}_start_time" type="time" name="schedules[{{ $dayOfWeek }}][start_time]" value="{{ $preferredSchedules[$dayOfWeek]->start_time ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    </div>

                                    <div>
                                        <label for="schedules_{{ $dayOfWeek }}_end_time" class="block text-sm font-medium text-gray-700">終了時間</label>
                                        <input id="schedules_{{ $dayOfWeek }}_end_time" type="time" name="schedules[{{ $dayOfWeek }}][end_time]" value="{{ $preferredSchedules[$dayOfWeek]->end_time ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="ml-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                更新する
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
