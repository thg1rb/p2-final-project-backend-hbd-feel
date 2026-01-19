<x-app-layout>
    <div class="p-10">
        <a class="flex gap-2 mb-10" href="{{ route('main.dashboard') }}">
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
                    <p class="mb-3">เลือกปีการศึกษาและภาคการศึกษา</p>
                    <div class="flex gap-5">
                        <select name="year" id=""
                            class="border border-gray-300  rounded-xl w-full cursor-pointer focus:outline-primary focus:border-gray-300 focus:ring-offset-0 focus:ring-0 flex-1">
                            <option value="">ทั้งหมด</option>
                            @foreach ($allYears as $year)
                                <option value={{ $year }} @selected($targetYear == $year)>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                        <select name="semester" id=""
                            class="border border-gray-300  rounded-xl w-full cursor-pointer focus:outline-primary focus:border-gray-300 focus:ring-offset-0 focus:ring-0 flex-1">
                            <option value="">ทั้งหมด</option>
                            @foreach ($allSemesters as $semester)
                                <option value={{ $semester }} @selected($targetSemester == $semester)>
                                    {{ $semester }}</option>
                            @endforeach
                        </select>
                        <button type="submit"
                            class="bg-primary rounded-xl text-white p-3 hover:scale-95 active:scale-90 transition-all">คัดกรอง</button>
                    </div>
                </form>
                @if ($users->isNotEmpty())
                    <div class="rounded-xl border border-gray-300 overflow-hidden bg-white">
                        <table class="w-full">
                            <thead class="divide-y border-b bg-gray-100">
                                <tr class="divide-x">
                                    <th class=" p-4 text-start">ชื่อนิสิต</th>
                                    <th class="  p-4 text-start">ประเภทรางวัล</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach ($users as $user)
                                    <tr class="divide-x">
                                        <td class=" p-4">{{ $user->name }} </td>
                                        <td class=" p-4">{{ $user->awards->first()->name }}</td>
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
                <div class="flex justify-end">
                    <div class="flex gap-4 items-center">
                        @if ($users->onFirstPage())
                            <x-icon name="arrow-head-left" class="stroke-gray-300"></x-icon>
                        @else
                            <a href="{{ $users->previousPageUrl() }}">
                                <x-icon name="arrow-head-left"></x-icon>
                            </a>
                        @endif
                        <p class="border rounded-xl py-2 px-5">1</p>
                        @if ($users->hasMorePages())
                            <a href="{{ $users->nextPageUrl() }}">
                                <x-icon name="arrow-head-right"></x-icon>
                            </a>
                        @else
                            <x-icon name="arrow-head-right" class="stroke-gray-300"></x-icon>
                        @endif
                    </div>
                </div>
            </div>
            <div class="bg-white p-8 border rounded-xl flex flex-col gap-8">
                <p>สรุปสถิติรางวัล
                    @if ($targetYear && $targetSemester)
                        ภาคการศึกษา {{ $targetYear }}/{{ $targetSemester }}
                    @elseif ($targetYear)
                        ประจำปีการศึกษา {{ $targetYear }}
                    @elseif ($targetSemester)
                        ประจำภาคการศึกษาที่ {{ $targetSemester }} ของทุกปี
                    @else
                        ทั้งหมด
                    @endif
                </p>
                <div class="flex gap-8">
                    <div
                        class="flex justify-center items-center flex-col border rounded-xl p-5 flex-1 bg-red-50 border-red-500">
                        <p class="mb-1 text-red-500"><span
                                class="font-bold text-4xl">{{ $awardStats->get('Extracurricular Activities', 0) }}</span>
                            รางวัล</p>
                        <p>ด้านกิจกรรมเสริมหลักสูตร</p>
                    </div>
                    <div
                        class="flex justify-center items-center flex-col border rounded-xl p-5 flex-1 bg-yellow-50 border-yellow-500">
                        <p class="mb-1 text-yellow-500"><span
                                class="font-bold text-4xl ">{{ $awardStats->get('Creativity & Innovation', 0) }}</span>
                            รางวัล
                        </p>
                        <p>ด้านความคิดสร้างสรรค์และนวัตกรรม</p>
                    </div>
                    <div
                        class="flex justify-center items-center flex-col border rounded-xl p-5 flex-1 bg-green-50 border-primary">
                        <p class="mb-1 text-primary"><span
                                class="font-bold text-4xl ">{{ $awardStats->get('Good Conduct', 0) }}</span> รางวัล</p>
                        <p>ด้านความประพฤติดี</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
