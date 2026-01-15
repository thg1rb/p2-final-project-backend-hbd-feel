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
                    <p class="text-2xl font-bold">10</p>
                </div>
                <div class="bg-primary p-3 rounded-xl">
                    <x-icon name='user' class="stroke-white"></x-icon>
                </div>
            </div>
            <div class="flex justify-between items-center bg-white rounded-xl p-5 shadow-sm flex-1">
                <div class="flex flex-col gap-2">
                    <p>รอบรับสมัครที่เปิด</p>
                    <p class="text-2xl font-bold">2568/1</p>
                </div>
                <div class="bg-primary p-3 rounded-xl">
                    <x-icon name='calendar' class="stroke-white"></x-icon>
                </div>
            </div>
            <div class="flex justify-between items-center bg-white rounded-xl p-5 shadow-sm flex-1">
                <div class="flex flex-col gap-2">
                    <p>คำขอทั้งหมด</p>
                    <p class="text-2xl font-bold">100</p>
                </div>
                <div class="bg-primary p-3 rounded-xl">
                    <x-icon name='book' class="stroke-white"></x-icon>
                </div>
            </div>
        </div>
        <div>
            <p class="font-bold mb-4 text-xl">เมนูหลัก</p>
            <div class="flex gap-4">
                <button
                    class="group bg-white rounded-lg p-8 cursor-pointer flex flex-1 border shadow-sm hover:border-primary hover:shadow-lg transition-all">
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
                <button
                    class= "group bg-white rounded-lg p-8 cursor-pointer flex flex-1 border shadow-sm hover:border-primary hover:shadow-lg transition-all">
                    <div class="flex-1 flex flex-col items-start gap-4">
                        <div class=" bg-[#e8f5ef] p-5 rounded-xl flex justify-center items-center w-fit">
                            <x-icon name='calendar' class="stroke-primary"></x-icon>
                        </div>
                        <div class="flex flex-col justify-start gap-1">
                            <p class="font-bold text-start text-lg">
                                จัดการรอบการรับสมัคร
                            </p>
                            <p class="text-gray-400 text-sm text-start">
                                สร้างและจัดการรอบรับสมัคร
                            </p>
                        </div>
                    </div>
                    <x-icon name='arrow-right' class="stroke-gray-400 group-hover:stroke-primary"></x-icon>
                </button>
                <button
                    class="group bg-white rounded-lg p-8 cursor-pointer flex flex-1 border shadow-sm hover:border-primary hover:shadow-lg transition-all">
                    <div class="flex-1 flex flex-col items-start gap-4">
                        <div class=" bg-[#e8f5ef] p-5 rounded-xl flex justify-center items-center w-fit">
                            <x-icon name='trophy' class="stroke-primary"></x-icon>
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
                </button>
            </div>
        </div>
        <div>
            {{-- Display Content Here --}}
        </div>
    </div>
</x-app-layout>
