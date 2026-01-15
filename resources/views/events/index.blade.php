<x-app-layout>
    <div class="px-[40px] flex flex-col gap-y-12">

        {{-- Header --}}
        <div class="flex flex-row justify-between items-center">
            <div>
                <h1 class="font-bold text-[32px]">จัดการรอบการให้รางวัล</h1>
                <p class="font-light text-[16px]">เพิ่ม แก้ไข หรือลบข้อมูลรอบการให้รางวัลในระบบ</p>
            </div>
            <button
                class="px-[10px] py-[6px] flex flex-row justify-center items-center gap-x-[10px] bg-[#99C3B2] text-white rounded-md transition-all hover:scale-105">
                <x-icon name="plus" size="30"/>
                <p class="font-semibold text-[20px]">เพิ่มรอบการให้รางวัล</p>
            </button>
        </div>

        {{-- Table --}}
        <div class="w-full p-5 flex flex-col gap-y-6 bg-white shadow-sm rounded-lg">
            <div class="w-full flex flex-row gap-x-2.5">
                <div class="relative flex-1">
                    <x-icon name="search" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
                    <input placeholder="ค้นหารอบการให้รางวัล"
                           class="w-full rounded-md border-slate-300 pl-10 placeholder:font-light placeholder:text-slate-400">
                </div>
                <select class="px-10 py-1.5 flex-2 border-slate-300 font-semibold text-[20px] rounded-md">
                    <option value="1">ทดสอบ 1</option>
                    <option value="2">ทดสอบ 2</option>
                </select>
                <button class="px-10 py-1.5 flex-2 bg-[#99C3B2] font-semibold text-white text-[20px] rounded-md">
                    ค้นหา
                </button>
            </div>
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr class="border border-slate-300">
                        <th class="px-6 py-3 text-left">รอบการให้รางวัล</th>
                        <th class="px-6 py-3 text-left">ปีการศึกษา</th>
                        <th class="px-6 py-3 text-left">ภาคเรียน</th>
                        <th class="px-6 py-3 text-center">สถานะ</th>
                        <th class="px-6 py-3 text-center">ดำเนินการ</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    {{-- TODO: Check if events not empty --}}
                    {{-- TODO: Iterate through the events --}}
                    <tr class="border border-slate-300">
                        <td class="px-6 py-3 text-left">รอบพิเศษปีใหม่</td>
                        <td class="px-6 py-3 text-left">2568</td>
                        <td class="px-6 py-3 text-left">2</td>
                        <td class="px-6 py-3 text-center">เปิดการรับสมัคร</td>
                        <td class="px-6 py-3 text-center">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button>
                                        <x-icon name="ellipsis" />
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile.edit')" class="flex flex-row gap-x-2 justify-start items-center">
                                        <x-icon name="eye" size="20" />
                                        {{ __('ดูรายละเอียด') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('profile.edit')" class="flex flex-row gap-x-2 justify-start items-center">
                                        <x-icon name="square-pen" size="20" />
                                        {{ __('แก้ไขข้อมูล') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('profile.edit')" class="flex flex-row gap-x-2 justify-start items-center hover:bg-red-200 hover:text-red-700">
                                        <x-icon name="trash-2" size="20" />
                                        {{ __('ลบข้อมูล') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="w-full flex flex-row justify-between items-center">
                <p>
                    หน้าที่ 1 จาก 8
                </p>
                <div class="flex flex-row gap-x-5">
                    <button class="w-[120px] py-1.5 bg-[#99C3B2] font-semibold text-white text-[20px] rounded-md">ย้อนกลับ</button>
                    <button class="w-[120px] py-1.5 bg-[#99C3B2] font-semibold text-white text-[20px] rounded-md">ถัดไป</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
