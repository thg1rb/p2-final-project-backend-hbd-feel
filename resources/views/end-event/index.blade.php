<x-app-layout>
    <div class="p-10">
        <a class="flex gap-2 mb-10" href="{{ route('main') }}">
            <x-icon name="arrow-left"></x-icon>
            <p>กลับหน้าหลัก</p>
        </a>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col gap-7">

                <div>
                    <h2 class="text-2xl font-bold">ลงนามประกาศนิสิตดีเด่น</h2>
                    <p class="text-gray-500">ลงนามประกาศนิสิตดีเด่นทั้งหมดที่ได้รับการอนุมัติครบทุกขั้นตอนแล้ว</p>
                </div>

                {{-- ส่วนที่ 1: เช็คว่าไม่มีงานค้าง (In Progress) --}}
                @if ($stats['totalInprogress'] !== 0 && !$hasParams)
                    <div class="flex flex-col gap-10">
                        <div class="flex items-center gap-5 rounded-2xl bg-gray-100 p-8">
                            <div class="rounded-2xl bg-gray-200 p-5 text-gray-600">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div class="flex flex-col gap-2">
                                <p class="text-xl font-bold">ขณะนี้ไม่อยู่ในช่วงพิจารณาลงนามประกาศรางวัล</p>
                                <p class="text-sm text-gray-400">ระบบจะเปิดให้พิจารณาลงนามเมื่อใบคำร้องทั้งหมดเสร็จสิ้น
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ส่วนที่ 2: แสดงตารางและปุ่มอัปโหลดเมื่อมีข้อมูล --}}
                @if ($applications->count() > 0 || $hasParams)
                    <div class="flex flex-col gap-7">

                        @if ($applications->count() > 0)
                            <div
                                class="flex flex-col rounded-2xl bg-gradient-to-r from-blue-500 to-blue-300 p-10 text-white">
                                <div class="flex flex-col items-start gap-8">
                                    <div class="flex items-center gap-5">
                                        <div class="bg-blue-400 p-3 rounded-2xl">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xl font-bold">ลงนามประกาศนิสิตดีเด่นประจำภาคเรียน</p>
                                            <p class="text-sm opacity-80">
                                                ดาวน์โหลดข้อมูลนิสิตที่ได้รับรางวัลเพื่อเสนอให้อธิการบดีลงนาม</p>
                                        </div>
                                    </div>

                                    <div class="flex w-full items-stretch gap-5">
                                        <a href="{{ route('end-event.pdf') }}"
                                            class="flex items-center gap-2 rounded-xl bg-white px-6 py-3 font-semibold text-blue-500 shadow-lg">
                                            <span>ดาวน์โหลดข้อมูลนิสิตดีเด่น</span>
                                        </a>

                                        <form action="{{ route('end-event.upload') }}" method="POST"
                                            enctype="multipart/form-data" class="flex flex-1 items-center gap-4">
                                            @csrf
                                            <input type="hidden" name="event_id"
                                                value="{{ $applications[0]->event_id ?? '' }}">
                                            <input name="document" type="file" accept=".pdf" required
                                                class="block flex-1 cursor-pointer rounded-xl border border-white/30 p-3 text-xs text-white file:mr-4 file:rounded-lg file:border-0 file:bg-white file:text-blue-500">
                                            <button type="submit"
                                                class="rounded-xl bg-white px-5 py-2 text-sm font-semibold text-blue-500">อัปโหลด</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif {{-- ปิด @if ($applications->count() > 0) --}}

                        {{-- <div class="bg-white rounded-xl shadow overflow-hidden">
                            <table class="min-w-full">
                                <thead class="bg-gray-50 border-b">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            ชื่อ-นามสกุล</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            ประเภทรางวัล</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($applications as $app)
                                        <tr>
                                            <td class="px-6 py-4">{{ $app->user->firstName }} {{ $app->user->lastName }}
                                            </td>
                                            <td class="px-6 py-4">{{ $app->award->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> --}}

                    </div> {{-- ปิด div ของส่วนที่ 2 --}}
                @else
                    <div class="text-center p-20 bg-white rounded-xl shadow">
                        <p class="text-gray-400">ยังไม่มีรายชื่อนิสิตที่รอพิจารณา</p>
                    </div>
                @endif {{-- ปิด @if ($applications->count() > 0 || $hasParams) --}}

            </div>
        </div>
    </div>
</x-app-layout>
