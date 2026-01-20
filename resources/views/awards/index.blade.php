<x-app-layout>
    <div class="p-10 flex flex-col gap-y-7">
        <a class="flex gap-2" href="{{ route('main') }}">
            <x-icon name="arrow-left"></x-icon>
            <p>กลับหน้าหลัก</p>
        </a>

        {{-- Header --}}
        <div class="flex flex-col justify-center items-center">
            <h1 class="font-bold text-[32px]">จัดการหมวดรางวัล</h1>
            <p class="font-light text-[16px]">เพิ่ม แก้ไข หรือลบข้อมูลรางวัลต่าง ๆ</p>
        </div>
        <div class="flex justify-end items-end">
            <a href="{{ route('awards.create') }}"
               class="w-1/7 px-[10px] py-[6px] flex flex-row justify-center items-center gap-x-[10px] bg-primary text-white rounded-md transition-all hover:scale-105">
                <x-icon name="plus" size="30" />
                <p class="font-semibold text-[20px]">เพิ่มหมวดรางวัล</p>
            </a>
        </div>
        <div class="w-full p-5 flex flex-col gap-y-6 bg-white shadow-sm rounded-xl">
            <table>
                <thead class="border-b bg-gray-100 divide-y">
                    <tr class="divide-x">
                        <th class="px-6 py-3 text-center cursor-pointer hover:bg-gray-200 transition">หมวดรางวัล</th>
                        <th class="px-6 py-3 text-center cursor-pointer hover:bg-gray-200 transition">รางวัล</th>
                        <th class="px-2 py-3 text-center cursor-pointer hover:bg-gray-200 transition">ดำเนินการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($awards as $award)
                        <tr class="divide-x">
                            <td class="px-6 py-3 text-center">{{ $award->name }}</td>
                            <td class="px-6 py-3 text-center">{{ NumberFormatter::create(app()->getLocale(), NumberFormatter::DECIMAL)->format($award->reward) }}</td>
                            <td class="px-2 py-3 text-center flex items-center justify-center gap-2">
                                <a href="{{ route('awards.edit', $award) }}" class="text-blue-600 hover:underline">
                                   แก้ไข
                                </a>
                                 |
                                <form action="{{ route('awards.destroy', $award) }}" method="POST" onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบหมวดรางวัลนี้?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="in-line text-red-600 hover:underline">
                                        ลบ
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                            <tr class="divide-x">
                                <td colspan="6" class="px-6 py-32 text-center text-slate-500">
                                    ไม่พบหมวดรางวัล
                                </td>
                            </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="w-full flex flex-row justify-end items-center">
            <div class="flex flex-row items-center  gap-x-5">
                {{-- Back Button --}}
                @if ($awards->onFirstPage())
                    <button disabled class="cursor-not-allowed">
                        <x-icon name="arrow-head-left" class="stroke-gray-300" />
                    </button>
                @else
                    <a href="{{ $awards->previousPageUrl() }}">
                        <x-icon name="arrow-head-left" />
                    </a>
                @endif

                <p class="border rounded-xl py-2 px-5">{{ $awards->currentPage() }}</p>

                {{-- Next Button --}}
                @if ($awards->hasMorePages())
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
</x-app-layout>
