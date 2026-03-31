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
                @if ($stats['totalInprogress'] !== 0 || !$event)
                    <div class="flex flex-col gap-10 border-border border-2 rounded-xl">
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
                @else
                    <div class="flex flex-col gap-7">

                        <div class="flex flex-col gap-10">
                            <div
                                class="flex flex-col rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 p-10 text-white">
                                <div class="flex flex-col items-start gap-8">
                                    <div class="flex items-center gap-5">
                                        <div class="bg-blue-400 p-3 rounded-2xl">
                                            <x-icon name="download"/>
                                        </div>
                                        <div>
                                            <p class="text-xl font-bold">ดาวน์โหลดข้อมูลนิสิตดีเด่น</p>
                                            <p class="text-sm opacity-80">
                                                กรอกชื่อผู้ลงนาม (อธิการบดี) และดาวน์โหลดเอกสารเพื่อนำไปลงนาม</p>
                                        </div>
                                    </div>
                                    <form action="{{ route('end-event.pdf') }}" method="GET"
                                          class="flex gap-3 w-full">
                                        <input type="text" name="signer_name" placeholder="ชื่อนามสกุล ผู้ลงนาม"
                                               class="w-full flex-1 rounded-xl border-none text-black" required>

                                        <div class="flex items-stretch gap-5">
                                            <button type="submit"
                                                    class="flex items-center gap-2 rounded-xl bg-white px-6 py-3 font-semibold text-blue-500 shadow-lg active:opacity-80">
                                                <span>ดาวน์โหลดข้อมูลนิสิตดีเด่น</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="flex justify-center items-center w-full">
                                <div class=" border-2 border-dashed border-blue-500 p-4 rounded-full">
                                    <x-icon name="arrow-down" class="stroke-blue-500 animate-bounce"
                                            size="40"/>
                                </div>
                            </div>

                            <div
                                class="flex flex-col rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 p-10 text-white">
                                <div class="flex flex-col items-start gap-8">
                                    <div class="flex items-center gap-5">
                                        <div class="bg-emerald-400 p-3 rounded-2xl">
                                            <x-icon name="upload"/>
                                        </div>
                                        <div>
                                            <p class="text-xl font-bold">อัปโหลดเอกสารที่ลงนามแล้ว</p>
                                            <p class="text-sm opacity-80">
                                                เลือกไฟล์เอกสารที่ลงนามเรียบร้อยแล้วเพื่ออัปโหลดกลับเข้าระบบ</p>
                                        </div>
                                    </div>

                                    <div class="flex w-full items-stretch gap-5">
                                        <form action="{{ route('end-event.upload') }}" method="POST"
                                              enctype="multipart/form-data" class="flex flex-1 items-center gap-4">
                                            @csrf
                                            <input type="hidden" name="event_id"
                                                   value="{{ $event->id ?? '' }}">
                                            <input name="document" type="file" accept=".pdf" required
                                                   class="block flex-1 cursor-pointer rounded-xl border border-white p-3 text-white file:mr-4 file:rounded-lg file:border-0 file:bg-white file:text-emerald-500">
                                            <button type="submit"
                                                    class="flex items-center gap-2 rounded-xl bg-white px-6 py-3 font-semibold text-blue-500 shadow-lg active:opacity-80">
                                                อัปโหลดไฟล์ที่ลงนาม
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
