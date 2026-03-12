@php
    function getSortUrl($column)
    {
        $sorts = request()->input('sorts', []);
        $currentDirection = $sorts[$column] ?? null;
        $nextDirection = match ($currentDirection) {
            'asc' => 'desc',
            'desc' => null,
            default => 'asc',
        };

        if ($nextDirection) {
            $sorts[$column] = $nextDirection;
        } else {
            unset($sorts[$column]);
        }
        return request()->fullUrlWithQuery(['sorts' => $sorts]);
    }

    function getSortingIcon($column)
    {
        $sorts = request()->input('sorts', []);
        $direction = $sorts[$column] ?? null;
        if (!$direction) {
            return '';
        }

        $priority = array_search($column, array_keys($sorts)) + 1;
        $badge =
            count($sorts) > 1
                ? "<span class='text-[10px] ml-1 bg-gray-200 text-gray-700 px-1 rounded'>{$priority}</span>"
                : '';

        $icon =
            $direction === 'asc'
                ? '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16"><path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/></svg>'
                : '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-up-fill" viewBox="0 0 16 16"><path d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592a1 1 0 0 0 .753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z"/></svg>';

        return "<div class='flex items-center'>{$icon}{$badge}</div>";
    }
@endphp

<x-app-layout>
    <div class="p-10 flex flex-col gap-y-7">
        <a class="flex gap-2" href="{{ route('main') }}">
            <x-icon name="arrow-left"></x-icon>
            <p>กลับหน้าหลัก</p>
        </a>

        <div class="flex flex-col md:flex-row gap-y-5 justify-between items-center">
            <div class="flex-col flex gap-2">
                <h1 class="font-bold text-2xl">จัดการข้อมูลคณะ</h1>
                <p class="text-gray-400">เพิ่ม ลบ หรือแก้ไขข้อมูลคณะและวิทยาเขต</p>
            </div>
            <a href="{{ route('faculties.create') }}"
                class="px-[10px] py-[6px] w-full md:w-fit flex flex-row justify-center items-center gap-x-[10px] bg-primary text-white rounded-md transition-all hover:scale-95 active:scale-90">
                <x-icon name="plus" size="30" />
                <p class="p-2">เพิ่มคณะ</p>
            </a>
        </div>

        <div class="w-full p-5 flex flex-col gap-y-6 bg-white shadow-sm rounded-xl">
            <form action="{{ route('faculties.index') }}" method="GET"
                class="w-full flex flex-col md:flex-row gap-2.5">
                <div class="relative flex-1">
                    <x-icon name="search" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
                    <input name="search" placeholder="ค้นหาชื่อคณะ หรือวิทยาเขต" value="{{ request('search') }}"
                        class="w-full border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm pl-10">
                </div>
                <button type="submit" class="px-10 py-1.5 bg-primary text-white rounded-md">ค้นหา</button>
            </form>

            <div class="rounded-xl border border-gray-300 bg-white overflow-x-auto">
                <table class="w-full min-w-max">
                    <thead class="divide-y border-b bg-gray-100">
                        <tr class="divide-x">
                            <th class="px-6 py-3 text-center cursor-pointer hover:bg-gray-200 transition">
                                <a href="{{ getSortUrl('name') }}"
                                    class="flex flex-row gap-x-2 justify-center items-center w-full h-full">
                                    ชื่อคณะ {!! getSortingIcon('name') !!}
                                </a>
                            </th>
                            <th class="px-6 py-3 text-center cursor-pointer hover:bg-gray-200 transition">
                                <a href="{{ getSortUrl('campus') }}"
                                    class="flex flex-row gap-x-2 justify-center items-center w-full h-full">
                                    วิทยาเขต {!! getSortingIcon('campus') !!}
                                </a>
                            </th>
                            <th class="px-6 py-3 text-center">ดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($faculties as $faculty)
                            <tr class="divide-x">
                                <td class="px-6 py-3 text-start">{{ $faculty->name }}</td>
                                <td class="px-6 py-3 text-start">
                                    {{ \App\Enums\CampusType::label($faculty->campus) }}
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <x-dropdown align="right" width="48">
                                        <x-slot name="trigger">
                                            <button><x-icon name="ellipsis" /></button>
                                        </x-slot>
                                        <x-slot name="content">
                                            <x-dropdown-link :href="route('faculties.show', $faculty)">
                                                {{ __('ดูรายละเอียด') }}
                                            </x-dropdown-link>
                                            <x-dropdown-link :href="route('faculties.edit', $faculty)">
                                                {{ __('แก้ไขข้อมูล') }}
                                            </x-dropdown-link>
                                            <form method="POST" action="{{ route('faculties.destroy', $faculty) }}">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100"
                                                    onclick="return confirm('ยืนยันการลบ?')">
                                                    {{ __('ลบข้อมูล') }}
                                                </button>
                                            </form>
                                        </x-slot>
                                    </x-dropdown>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-32 text-center text-slate-500">ไม่พบข้อมูลคณะ</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="w-full flex flex-row justify-end items-center mt-5">
                <div class="flex flex-row items-center  gap-x-5">
                    {{-- Back Button --}}
                    @if ($faculties->onFirstPage())
                        <button disabled class="cursor-not-allowed">
                            <x-icon name="arrow-head-left" class="stroke-gray-300" />
                        </button>
                    @else
                        <a href="{{ $faculties->previousPageUrl() }}">
                            <x-icon name="arrow-head-left" />
                        </a>
                    @endif

                    <p class="border rounded-xl py-2 px-5">{{ $faculties->currentPage() }}</p>

                    {{-- Next Button --}}
                    @if ($faculties->hasMorePages())
                        <a href="{{ $faculties->nextPageUrl() }}">
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
