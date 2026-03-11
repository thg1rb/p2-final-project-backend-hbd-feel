<x-app-layout>
    <div class="  p-10 flex flex-col gap-7">
        <div class="flex-col flex gap-2">
            <p class="font-bold text-2xl">แดชบอร์ดผู้ดูแลระบบ</p>
            <p class=" text-gray-400">จัดการผู้ใช้ รอบรับสมัคร และดูรายงานสรุป</p>
        </div>
        <div class="flex gap-4">
            <div class="flex justify-between items-center bg-white rounded-xl p-5 shadow-sm flex-1">
                <div class="flex flex-col gap-2">
                    <p>ผู้ใช้ทั้งหมด</p>
                    <p class="text-2xl font-bold">{{ $totalUser }}</p>
                </div>
                <div class="bg-primary p-3 rounded-xl">
                    <x-icon name='user' class="stroke-white"></x-icon>
                </div>
            </div>
            <div class="flex justify-between items-center bg-white rounded-xl p-5 shadow-sm flex-1">
                <div class="flex flex-col gap-2">
                    <p>รอบรับสมัครที่เปิด</p>
                    @if ($currentEvent === null)
                        <p class="text-2xl font-bold">ไม่มีรอบรับสมัครที่เปิดอยู่</p>
                    @else
                        <p class="text-2xl font-bold">{{ $currentEvent->academic_year . '/' . $currentEvent->semester }}
                        </p>
                    @endif
                </div>
                <div class="bg-primary p-3 rounded-xl">
                    <x-icon name='calendar' class="stroke-white"></x-icon>
                </div>
            </div>
            {{--            <div class="flex justify-between items-center bg-white rounded-xl p-5 shadow-sm flex-1"> --}}
            {{--                <div class="flex flex-col gap-2"> --}}
            {{--                    <p>รางวัลที่อนุมัติในรอบรับสมัครที่เปิด</p> --}}
            {{--                    <p class="text-2xl font-bold">{{ $currentAwardTotal }}</p> --}}
            {{--                </div> --}}
            {{--                <div class="bg-primary p-3 rounded-xl"> --}}
            {{--                    <x-icon name='book' class="stroke-white"></x-icon> --}}
            {{--                </div> --}}
            {{--            </div> --}}
        </div>
        <div>
            <p class="font-bold mb-4 text-xl">เมนูหลัก</p>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                <button
                    class="group bg-white rounded-lg p-8 cursor-pointer flex flex-1 border shadow-sm hover:border-primary hover:shadow-lg transition-all"
                    type="button" onclick="window.location.href='{{ route('users.index') }}'">
                    <div class="flex-1 flex flex-col items-start gap-4">
                        <div class=" bg-[#e8f5ef] p-5 rounded-xl flex justify-center items-center w-fit">
                            <x-icon name='book' class="stroke-primary"></x-icon>
                        </div>
                        <div class="flex flex-col justify-start gap-1">
                            <p class="font-bold text-start text-lg">
                                จัดการผู้ใช้
                            </p>
                            <p class="text-gray-400 text-sm text-start">
                                ดูแลและจัดการข้อมูลผู้ใช้ในระบบ
                            </p>
                        </div>
                    </div>
                    <x-icon name='arrow-right' class="stroke-gray-400 group-hover:stroke-primary"></x-icon>
                </button>
                <a href="{{ route('events.index') }}"
                    class= "group bg-white rounded-lg p-8 cursor-pointer flex flex-1 border shadow-sm hover:border-primary hover:shadow-lg transition-all">
                    <div class="flex-1 flex flex-col items-start gap-4">
                        <div class=" bg-[#e8f5ef] p-5 rounded-xl flex justify-center items-center w-fit">
                            <x-icon name='calendar' class="stroke-primary"></x-icon>
                        </div>
                        <div class="flex flex-col justify-start gap-1">
                            <p class="font-bold text-start text-lg">
                                จัดการรอบการให้รางวัล
                            </p>
                            <p class="text-gray-400 text-sm text-start">
                                เพิ่ม ลบ หรือแก้ไขข้อมูลรอบการให้รางวัลในระบบ
                            </p>
                        </div>
                    </div>
                    <x-icon name='arrow-right' class="stroke-gray-400 group-hover:stroke-primary"></x-icon>
                </a>
                <a href="{{ route('awards.index') }}"
                    class="group bg-white rounded-lg p-8 cursor-pointer flex flex-1 border shadow-sm hover:border-primary hover:shadow-lg transition-all">
                    <div class="flex-1 flex flex-col items-start gap-4">
                        <div class=" bg-[#e8f5ef] p-5 rounded-xl flex justify-center items-center w-fit">
                            <x-icon name='trophy' class="stroke-primary"></x-icon>
                        </div>
                        <div class="flex flex-col justify-start gap-1">
                            <p class="font-bold text-start text-lg">
                                จัดการรางวัล
                            </p>
                            <p class="text-gray-400 text-sm text-start">
                                เพิ่ม ลบ หรือแก้ไขข้อมูลหมวดรางวัลในระบบ
                            </p>
                        </div>
                    </div>
                    <x-icon name='arrow-right' class="stroke-gray-400 group-hover:stroke-primary"></x-icon>
                </a>
                <a href="{{ route('report.award-report') }}"
                    class="group bg-white rounded-lg p-8 cursor-pointer flex flex-1 border shadow-sm hover:border-primary hover:shadow-lg transition-all">
                    <div class="flex-1 flex flex-col items-start gap-4">
                        <div class=" bg-[#e8f5ef] p-5 rounded-xl flex justify-center items-center w-fit">
                            <x-icon name='file-badge' class="stroke-primary"></x-icon>
                        </div>
                        <div class="flex flex-col justify-start gap-1">
                            <p class="font-bold text-start text-lg">
                                รายงานรางวัล
                            </p>
                            <p class="text-gray-400 text-sm text-start">
                                ดูสถิติและรายงานรางวัลนิสิตดีเด่น
                            </p>
                        </div>
                    </div>
                    <x-icon name='arrow-right' class="stroke-gray-400 group-hover:stroke-primary"></x-icon>
                </a>
                <a href="{{ route('end-event.index') }}"
                    class="group bg-white rounded-lg p-8 cursor-pointer flex flex-1 border shadow-sm hover:border-primary hover:shadow-lg transition-all">
                    <div class="flex-1 flex flex-col items-start gap-4">
                        <div class=" bg-[#e8f5ef] p-5 rounded-xl flex justify-center items-center w-fit">
                            <x-icon name='chancellor' class="stroke-primary"></x-icon>
                        </div>
                        <div class="flex flex-col justify-start gap-1">
                            <p class="font-bold text-start text-lg">
                                ลงนามอธิการบดีเพื่อจบช่วงการพิจารณา
                            </p>
                            <p class="text-gray-400 text-sm text-start">
                                นำเข้าไฟล์รายชื่อนิสิตที่ได้รับการลงนามจากอธิการบดีแล้วเพื่อจบช่วงพิจารณา
                            </p>
                        </div>
                    </div>
                    <x-icon name='arrow-right' class="stroke-gray-400 group-hover:stroke-primary"></x-icon>
                </a>
                <a href="{{ route('award-result.index') }}"
                   target="_blank"
                   class="group bg-white rounded-lg p-8 cursor-pointer flex flex-1 border shadow-sm hover:border-primary hover:shadow-lg transition-all">

                    <div class="flex-1 flex flex-col items-start gap-4">
                        <div class=" bg-[#e8f5ef] p-5 rounded-xl flex justify-center items-center w-fit">
                            <x-icon name='trophy' class="stroke-primary"></x-icon>
                        </div>

                        <div class="flex flex-col justify-start gap-1">
                            <p class="font-bold text-start text-lg">
                                ดูผลรางวัลนิสิตดีเด่น
                            </p>

                            <p class="text-gray-400 text-sm text-start">
                                เปิดหน้าเว็บไซต์เพื่อดูรายชื่อนิสิตที่ได้รับรางวัล
                            </p>
                        </div>
                    </div>

                    <x-icon name='arrow-right' class="stroke-gray-400 group-hover:stroke-primary"></x-icon>
                </a>
            </div>
        </div>
        <div>
            {{-- Display Content Here --}}
        </div>
    </div>
</x-app-layout>
