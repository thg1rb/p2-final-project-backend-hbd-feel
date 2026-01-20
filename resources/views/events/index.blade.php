@php
    function getSortUrl($column) {
        $sorts = request()->input('sorts', []);

        // Determine current state of this specific column
        $currentDirection = $sorts[$column] ?? null;

        // Cycle: ASC -> DESC -> REMOVE
        $nextDirection = match($currentDirection) {
            'asc' => 'desc',
            'desc' => null,
            default => 'asc',
        };

        // Update the sorts array
        if ($nextDirection) {
            $sorts[$column] = $nextDirection;
        } else {
            unset($sorts[$column]);
        }

        return request()->fullUrlWithQuery(['sorts' => $sorts]);
    }

    function getSortingIcon($column) {
        $sorts = request()->input('sorts', []);
        $direction = $sorts[$column] ?? null;

        if (!$direction) return '';

        // Calculate priority (1, 2, 3...) based on order in the array
        $priority = array_search($column, array_keys($sorts)) + 1;
        $badge = count($sorts) > 1 ? "<span class='text-[10px] ml-1 bg-gray-200 text-gray-700 px-1 rounded'>{$priority}</span>" : '';

        $icon = '';
        if ($direction === 'asc') {
            $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16"><path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/></svg>';
        } else {
            $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-up-fill" viewBox="0 0 16 16"><path d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592a1 1 0 0 0 .753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z"/></svg>';
        }

        return "<div class='flex items-center'>{$icon}{$badge}</div>";
    }
@endphp

<x-app-layout>
    <div class="p-10 flex flex-col gap-y-7">
        <a class="flex gap-2" href="{{ route('main.dashboard') }}">
            <x-icon name="arrow-left"></x-icon>
            <p>กลับหน้าหลัก</p>
        </a>

        {{-- Header --}}
        <div class="flex flex-row justify-between items-center">
            <div>
                <h1 class="font-bold text-[32px]">จัดการรอบการให้รางวัล</h1>
                <p class="font-light text-[16px]">เพิ่ม แก้ไข หรือลบข้อมูลรอบการให้รางวัลในระบบ</p>
            </div>
            <a
                href="{{ route('events.create') }}"
                class="px-[10px] py-[6px] flex flex-row justify-center items-center gap-x-[10px] bg-primary text-white rounded-md transition-all hover:scale-105">
                <x-icon name="plus" size="30"/>
                <p class="font-semibold text-[20px]">เพิ่มรอบการให้รางวัล</p>
            </a>
        </div>

        {{-- Table --}}
        <div class="w-full p-5 flex flex-col gap-y-6 bg-white shadow-sm rounded-xl">
            <form action="{{ route('events.index') }}" method="GET" class="w-full flex flex-row gap-x-2.5">
                <div class="relative flex-1">
                    <x-icon name="search" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
                    <input
                        name="search"
                        placeholder="ค้นหารอบการให้รางวัล"
                        value="{{ request('search') }}"
                        class="w-full rounded-md border-slate-300 pl-10 placeholder:font-light placeholder:text-slate-400"
                    >
                </div>
                <select name="status" class="px-10 py-1.5 flex-2 border-slate-300 font-semibold text-[18px] rounded-md">
                    <option value="">ทั้งหมด</option>
                    <option value="OPENED" {{ request('status') === 'OPENED' ? 'selected' : '' }}>เปิดรับสมัคร</option>
                    <option value="CLOSED" {{ request('status') === 'CLOSED' ? 'selected' : '' }}>ปิดรับสมัคร</option>
                </select>
                <button type="submit" class="px-10 py-1.5 flex-2 bg-primary font-semibold text-white text-[18px] rounded-md">
                    ค้นหา
                </button>
            </form>
            <div class="rounded-xl border border-gray-300 bg-white">
                <table class="w-full">
                    <thead class="divide-y border-b bg-gray-100">
                        <tr class="divide-x">
                            <th class="px-6 py-3 text-center cursor-pointer hover:bg-gray-200 transition">
                                <a href="{{ getSortUrl('academic_year') }}" class="flex flex-row gap-x-2 justify-center items-center w-full h-full">
                                    ปีการศึกษา
                                    {!! getSortingIcon('academic_year') !!}
                                </a>
                            </th>
                            <th class="px-6 py-3 text-center cursor-pointer hover:bg-gray-200 transition">
                                <a href="{{ getSortUrl('semester') }}" class="flex flex-row gap-x-2 justify-center items-center w-full h-full">
                                    ภาคเรียน
                                    {!! getSortingIcon('semester') !!}
                                </a>
                            </th>
                            <th class="px-6 py-3 text-center cursor-pointer hover:bg-gray-200 transition">
                                <a href="{{ getSortUrl('start_date') }}" class="flex flex-row gap-x-2 justify-center items-center w-full h-full">
                                    วันที่เริ่มต้น
                                    {!! getSortingIcon('start_date') !!}
                                </a>
                            </th>
                            <th class="px-6 py-3 text-center cursor-pointer hover:bg-gray-200 transition">
                                <a href="{{ getSortUrl('end_date') }}" class="flex flex-row gap-x-2 justify-center items-center w-full h-full">
                                    วันที่สิ้นสุด
                                    {!! getSortingIcon('end_date') !!}
                                </a>
                            </th>
                            <th class="px-6 py-3 text-center">สถานะ</th>
                            <th class="px-6 py-3 text-center">ดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($events as $event)
                            <tr class="divide-x">
                                <td class="px-6 py-3 text-center">{{ $event->academic_year }}</td>
                                <td class="px-6 py-3 text-center">{{ $event->semester }}</td>
                                <td class="px-6 py-3 text-center">{{ \Carbon\Carbon::parse($event->start_date)->addYears(543)->locale('th')->translatedFormat('j F Y') }}</td>
                                <td class="px-6 py-3 text-center">{{ \Carbon\Carbon::parse($event->end_date)->addYears(543)->locale('th')->translatedFormat('j F Y') }}</td>
                                <td class="px-6 py-3 flex justify-center text-center">
                                    @if($event->status === \App\Enums\Status::OPENED)
                                        <p class="w-fit px-2 py-1 bg-green-200 text-green-700 rounded-md">
                                            เปิดรับสมัคร
                                        </p>
                                    @else
                                        <p class="w-fit px-2 py-1 bg-red-200 text-red-700 rounded-md">
                                            ปิดรับสมัคร
                                        </p>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <x-dropdown align="right" width="48">
                                        <x-slot name="trigger">
                                            <button>
                                                <x-icon name="ellipsis" />
                                            </button>
                                        </x-slot>
                                        <x-slot name="content">
                                            <x-dropdown-link :href="route('events.show', $event)" class="flex flex-row gap-x-2 justify-start items-center">
                                                <x-icon name="eye" size="20" />
                                                {{ __('ดูรายละเอียด') }}
                                            </x-dropdown-link>
                                            <x-dropdown-link :href="route('events.edit', $event)" class="flex flex-row gap-x-2 justify-start items-center">
                                                <x-icon name="square-pen" size="20" />
                                                {{ __('แก้ไขข้อมูล') }}
                                            </x-dropdown-link>
                                            <x-dropdown-link :href="route('events.edit', $event)" class="flex flex-row gap-x-2 justify-start items-center hover:bg-red-200 hover:text-red-700">
                                                <x-icon name="trash-2" size="20" />
                                                {{ __('ลบข้อมูล') }}
                                            </x-dropdown-link>
                                        </x-slot>
                                    </x-dropdown>
                                </td>
                            </tr>
                        @empty
                            <tr class="divide-x">
                                <td colspan="5" class="px-6 py-32 text-center text-slate-500">
                                    ไม่พบข้อมูลรอบการให้รางวัล
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="w-full flex flex-row justify-end items-center">
                <div class="flex flex-row items-center  gap-x-5">
                    {{-- Back Button --}}
                    @if ($events->onFirstPage())
                        <button disabled class="cursor-not-allowed">
                            <x-icon name="arrow-head-left" class="stroke-gray-300" />
                        </button>
                    @else
                        <a href="{{ $events->previousPageUrl() }}">
                            <x-icon name="arrow-head-left" />
                        </a>
                    @endif

                    <p class="border rounded-xl py-2 px-5">{{ $events->currentPage() }}</p>

                    {{-- Next Button --}}
                    @if ($events->hasMorePages())
                        <a href="{{ $events->nextPageUrl() }}">
                            <x-icon name="arrow-head-right"></x-icon>
                        </a>
                    @else
                        <button disabled class="cursor-not-allowed">
                            <x-icon name="arrow-head-right" class="stroke-gray-300"></x-icon>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
