{{-- @var \App\Models\Application $application --}}
<?php
use App\Enums\ApprovalStatus;
use App\Enums\RoleLevel;
use App\Enums\Status;
?>
<x-app-layout>
    <div class="p-10">
        <a class="flex gap-2 mb-10" href="{{ route('main') }}">
            <x-icon name="arrow-left"></x-icon>
            <p>กลับหน้าหลัก</p>
        </a>
        <div class="flex flex-col gap-7">
            <div class="flex-col flex gap-2">
                <p class="font-bold text-2xl">รายงานรางวัล</p>
                <p class=" text-gray-400">ดูสถิติและรายงานสรุปต่างๆ</p>
            </div>
            <div class="bg-white p-8 border rounded-xl flex flex-col gap-8">
                <form action="{{ route('report.award-report') }}">
                    <p class="mb-8 font-semibold">
                        เลือกปีการศึกษาและภาคการศึกษาหรือคัดกรองตามชื่อนิสิตหรือคัดกรองตามหมวดหมู่รางวัล
                    </p>
                    <div class="flex gap-5 items-center">
                        <div class="flex flex-col gap-2 flex-1">
                            <p>ค้นหาด้วยรหัสใบสมัคร ชื่อจริง รหัสนิสิต</p>
                            <input type="text" name="search" placeholder="ค้นหาด้วยรหัสใบสมัคร ชื่อจริง รหัสนิสิต"
                                class="border border-gray-300  rounded-xl w-full cursor-pointer focus:outline-primary focus:border-gray-300 focus:ring-offset-0 focus:ring-0 flex-1">
                        </div>
                        <div class="flex flex-col gap-2 flex-1">
                            <p>คัดกรองด้วยปีการศึกษา</p>
                            <select name="year" id=""
                                class="border border-gray-300  rounded-xl w-full cursor-pointer focus:outline-primary focus:border-gray-300 focus:ring-offset-0 focus:ring-0 flex-1">
                                <option value="">ทั้งหมด</option>
                                @foreach ($allYears as $year)
                                    <option value={{ $year }} @selected($targetYear == $year)>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col gap-2 flex-1">
                            <p>คัดกรองด้วยภาคการศึกษา</p>
                            <select name="semester" id=""
                                class="border border-gray-300  rounded-xl w-full cursor-pointer focus:outline-primary focus:border-gray-300 focus:ring-offset-0 focus:ring-0 flex-1">
                                <option value="">ทั้งหมด</option>
                                @foreach ($allSemesters as $semester)
                                    <option value={{ $semester }} @selected($targetSemester == $semester)>
                                        {{ $semester }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col gap-2 flex-1">
                            <p>คัดกรองด้วยประเภทของรางวัล</p>
                            <select name="type" id=""
                                class="border border-gray-300  rounded-xl w-full cursor-pointer focus:outline-primary focus:border-gray-300 focus:ring-offset-0 focus:ring-0 flex-1">
                                <option value="">ทั้งหมด</option>
                                <option value="ด้านกิจกรรมเสริมหลักสูตร">ด้านกิจกรรมเสริมหลักสูตร</option>
                                <option value="ด้านความคิดสร้างสรรค์และนวัตกรรม">ด้านความคิดสร้างสรรค์และนวัตกรรม
                                </option>
                                <option value="ด้านความประพฤติดี">ด้านความประพฤติดี</option>
                            </select>
                        </div>
                        <button type="submit"
                            class="bg-primary text-white p-3 hover:scale-95 active:scale-90 transition-all rounded-full h-fit">
                            <x-icon name="search" size="20" class="stroke-gray-400"></x-icon>
                        </button>
                    </div>
                </form>
                @if ($applications->isNotEmpty())
                    <div class="rounded-xl border border-gray-300 overflow-hidden bg-white">
                        <table class="w-full">
                            <thead class="divide-y border-b bg-gray-100">
                                <tr class="divide-x">
                                    <th class=" p-4 text-start">ชื่อนิสิต</th>
                                    <th class="  p-4 text-start">รหัสนิสิต</th>
                                    <th class="  p-4 text-start">ภาควิชา</th>
                                    <th class="  p-4 text-start">คณะ</th>
                                    <th class="  p-4 text-start">ประเภทรางวัล</th>
                                    <th class="  p-4 text-start">สถานะ</th>
                                    <th class="  p-4 text-start">วันที่ยื่น</th>
                                    <th class="  p-4 text-start"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach ($applications as $application)
                                    <tr class="divide-x">
                                        <td class="p-4">
                                            {{ $application->user->firstName }} {{ $application->user->lastName }}
                                        </td>

                                        <td class="p-4">
                                            {{ $application->student_id }}
                                        </td>

                                        <td class="p-4">
                                            {{ $application->user->department->name ?? 'ไม่ระบุ' }}
                                        </td>

                                        <td class="p-4">
                                            {{ $application->user->faculty->name ?? 'ไม่ระบุ' }}
                                        </td>

                                        <td class="p-4">
                                            {{ $application->award->name }}
                                        </td>

                                        <td class="p-4">
                                            @if ($application->status->value === ApprovalStatus::REJECTED->value)
                                                <div
                                                    class="rounded-full border border-red-400 bg-red-100 px-3 py-1 text-red-500 text-sm w-fit">
                                                    ปฏิเสธ
                                                </div>
                                            @elseif ($application->level->value === RoleLevel::BOARD->value && $application->event->status === \App\Enums\Status::CLOSED)
                                                <div
                                                    class="rounded-full border border-primary bg-green-50 px-3 py-1 text-primary text-sm w-fit">
                                                    อนุมัติ
                                                </div>
                                            @elseif (
                                                $application->status->value === ApprovalStatus::APPROVED->value &&
                                                    $application->level->value === RoleLevel::DEAN->value)
                                                <div
                                                    class="rounded-full border border-amber-400 bg-amber-100 px-3 py-1 text-amber-500 text-sm w-fit">
                                                    รอพิจารณา
                                                </div>
                                            @else
                                                <div
                                                    class="rounded-full border border-orange-400 bg-orange-50 px-3 py-1 text-orange-500 text-sm w-fit">
                                                    กำลังดำเนินการ
                                                </div>
                                            @endif
                                        </td>

                                        <td class="p-4">
                                            {{ $application->created_at->format('d/m/Y') }}
                                        </td>

                                        <td class="p-4">
                                            <a href="{{ route('report.show', $application->id) }}"
                                                class="text-blue-500 hover:underline">
                                                ดูรายละเอียด
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="justify-center items-center p-7 flex flex-col gap-5">
                        <x-icon name="frown" size="70" class="stroke-gray-400"></x-icon>
                        <p class=" text-gray-400">ไม่พบข้อมูลที่คุณคัดกรอง</p>
                    </div>
                @endif
                <div class="flex justify-between">
                    <div class="flex space-x-2">
                        <a href="{{ route('report.award-report', array_merge(request()->query(), ['export' => 'csv'])) }}"
                            class="btn btn-success flex items-center bg-blue-500 text-white p-3 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            นำออกข้อมูลรายชื่อนิสิต
                        </a>
                    </div>
                    <div class="flex gap-4 items-center">
                        @if ($applications->onFirstPage())
                            <x-icon name="arrow-head-left" class="stroke-gray-300"></x-icon>
                        @else
                            <a href="{{ $applications->previousPageUrl() }}">
                                <x-icon name="arrow-head-left"></x-icon>
                            </a>
                        @endif
                        <p class="border rounded-xl py-2 px-5">{{ $applications->currentPage() }}</p>
                        @if ($applications->hasMorePages())
                            <a href="{{ $applications->nextPageUrl() }}">
                                <x-icon name="arrow-head-right"></x-icon>
                            </a>
                        @else
                            <x-icon name="arrow-head-right" class="stroke-gray-300"></x-icon>
                        @endif
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>
