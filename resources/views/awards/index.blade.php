<x-app-layout>
    <div class="p-10 flex flex-col gap-y-7">
        <a class="flex gap-2" href="{{ route('main') }}">
            <x-icon name="arrow-left"></x-icon>
            <p>กลับหน้าหลัก</p>
        </a>

        {{-- Header --}}
        <div class="flex flex-col md:flex-row gap-y-5 justify-between items-center">
            <div class="flex-col flex gap-2">
                <h1 class="font-bold text-2xl">จัดการหมวดรางวัล</h1>
                <p class="text-gray-400">เพิ่ม แก้ไข หรือลบข้อมูลรางวัลในระบบ</p>
            </div>
            <a href="{{ $event ? route('awards.create') : "" }}"
                class="{{$event ? "bg-primary text-white transition-all hover:scale-105" : "bg-gray-500 cursor-not-allowed"}} px-[10px] py-[6px] w-full md:w-fit flex flex-row justify-center items-center gap-x-[10px] rounded-md">
                <x-icon name="plus" size="30" />
                <p class="p-2">เพิ่มหมวดรางวัล</p>
            </a>
        </div>

        {{-- Table --}}
        <div class="w-full p-5 flex flex-col gap-y-6 bg-white shadow-sm rounded-xl">
            <form action="{{ route('awards.index') }}" method="GET" class="w-full flex flex-col md:flex-row gap-2.5">
                <div class="relative flex-1">
                    <x-icon name="search" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
                    <input name="search" placeholder="ค้นหาชื่อหมวดรางวัล" value="{{ request('search') }}"
                        class="w-full rounded-md border-slate-300 pl-10 placeholder:font-light placeholder:text-slate-400">
                </div>
                <button type="submit" class="px-10 py-1.5 bg-primary text-white  rounded-md">
                    ค้นหา
                </button>
            </form>
            <div class="rounded-xl border border-gray-300 bg-white overflow-hidden">
                <table class="w-full">
                    <thead class="divide-y border-b bg-gray-100">
                        <tr class="divide-x">
                            <th class="px-6 py-3 text-left cursor-pointer hover:bg-gray-200 transition">หมวดรางวัล</th>
{{--                            <th class="px-6 py-3 text-left cursor-pointer hover:bg-gray-200 transition">รางวัล</th>--}}
                            <th class="px-2 py-3 text-center cursor-pointer hover:bg-gray-200 transition">ดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($awards as $award)
                            <tr class="divide-x">
                                <td class="px-6 py-3 text-left">{{ $award->name }}</td>
{{--                                <td class="px-6 py-3 text-left">--}}
{{--                                    {{ NumberFormatter::create(app()->getLocale(), NumberFormatter::DECIMAL)->format($award->reward) }}--}}
{{--                                </td>--}}
                                <td class="px-2 py-3 text-center flex items-center justify-center gap-2">
                                    <a href="{{ route('awards.edit', $award) }}"
                                        class="py-1 px-3 bg-blue-200 hover:bg-blue-300 font-semibold text-blue-700 rounded-md cursor-pointer transition-all hover:scale-105">
                                        แก้ไข
                                    </a>
                                    <form action="{{ route('awards.destroy', $award) }}" method="POST"
                                        onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบหมวดรางวัลนี้?');">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            class="py-1 px-3 bg-red-200 hover:bg-red-300 font-semibold text-red-700 rounded-md cursor-pointer transition-all hover:scale-105">
                                            ลบ
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr class="divide-x">
                                <td colspan="6" class="px-6 py-32 text-center text-slate-500">
                                    {{$event ? "ไม่พบหมวดรางวัล" : "ไม่มีรอบรางวัลที่เปิดอยู่"}}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="w-full flex flex-row justify-end items-center">
                <div class="flex flex-row items-center  gap-x-5">
                    {{-- Back Button --}}
                    @if ($awards && $awards->onFirstPage())
                        <button disabled class="cursor-not-allowed">
                            <x-icon name="arrow-head-left" class="stroke-gray-300" />
                        </button>
                    @elseif ($awards)
                        <a href="{{ $awards->previousPageUrl() }}">
                            <x-icon name="arrow-head-left" />
                        </a>
                    @endif

                    <p class="border rounded-xl py-2 px-5">{{$awards ? $awards->currentPage() : "0" }}</p>

                    {{-- Next Button --}}
                    @if ($awards && $awards->hasMorePages())
                        <a href="{{ $awards->nextPageUrl() }}">
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
