@php use App\Enums\AwardRegistrationState; @endphp
<x-app-layout>
    <div class="max-w-7xl mx-auto p-6 lg:p-10 flex flex-col gap-y-8">

        {{-- Header Section --}}
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">สวัสดี, {{ auth()->user()->firstName ." ". auth()->user()->lastName }}</h1>
                <p class="text-gray-500 mt-1">
                    รหัสนิสิต: {{ auth()->user()->studentId ?? 'N/A' }} | {{ auth()->user()->department ?? 'คณะ' }}
                </p>
            </div>
            <div class="flex gap-x-3">
                <a href="{{ route('award-registrations.create') }}" class="flex items-center gap-2 bg-[#226e64] text-white px-5 py-2.5 rounded-xl hover:bg-[#1a564d] transition-all shadow-sm">
                    <x-icon name="plus" size="20" />
                    <span class="font-medium">สมัครนิสิตดีเด่น</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 border border-gray-200 bg-white text-gray-600 px-5 py-2.5 rounded-xl hover:bg-gray-50 transition-all shadow-sm">
                        <x-icon name="trash-2" size="20" />
                        <span>ออกจากระบบ</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            {{-- Card 1: ทั้งหมด --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500 font-medium">การสมัครทั้งหมด</p>
                    <p class="text-4xl font-bold mt-1">{{ $allStats->count() }}</p>
                </div>
                <div class="bg-emerald-50 p-3 rounded-xl">
                    <x-icon name="book" class="text-emerald-600" size="32" />
                </div>
            </div>

            {{-- Card 2: รอพิจารณา --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500 font-medium">รอพิจารณา</p>

                    <p class="text-4xl font-bold mt-1">{{ $allStats->whereNotIn('status', [\App\Enums\AwardRegistrationState::REJECTED->value, \App\Enums\AwardRegistrationState::COMPLETED->value])->count() }}</p>
                </div>
                <div class="bg-orange-50 p-3 rounded-xl">
                    <x-icon name="eye" class="text-orange-500" size="32" />
                </div>
            </div>

            {{-- Card 3: อนุมัติ --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500 font-medium">อนุมัติแล้ว</p>
                    <p class="text-4xl font-bold mt-1 text-green-600">{{ $allStats->where('status', \App\Enums\AwardRegistrationState::COMPLETED->value)->count() }}</p>
                </div>
                <div class="bg-green-50 p-3 rounded-xl">
                    <x-icon name="trophy" class="text-green-500" size="32" />
                </div>
            </div>

            {{-- Card 4: ไม่ผ่าน --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500 font-medium">ไม่ผ่านการพิจารณา</p>
                    <p class="text-4xl font-bold mt-1 text-red-600">{{ $allStats->where('status', \App\Enums\AwardRegistrationState::REJECTED->value)->count() }}</p>
                </div>
                <div class="bg-red-50 p-3 rounded-xl">
                    <x-icon name="trash-2" class="text-red-500" size="32" />
                </div>
            </div>
        </div>

        @if($currentEvent)
            <div class="bg-[#2d6a4f] rounded-2xl p-6 text-white flex justify-between items-center shadow-md">
                <div class="flex items-center gap-5">
                    <div class="bg-white/20 p-4 rounded-xl">
                        <x-icon name="calendar" size="35" />
                    </div>
                    <div>
                        <h3 class="text-xl font-bold">รอบรับสมัครปัจจุบัน</h3>
                        <p class="opacity-90">ภาคเรียน {{ $currentEvent->semester ?? 'ภาคเรียนปัจจุบัน' }}/ {{ $currentEvent->academic_year ?? 'xxx'}}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="bg-[#f39c12] text-white px-4 py-1 rounded-full text-sm font-bold">เปิดรับสมัคร</span>
                    <p class="mt-2 text-sm font-light">หมดเขต: {{ $currentEvent->end_date ?? '-' }}</p>
                </div>
            </div>
        @endif

        {{-- Application History Section --}}
        <div class="space-y-4">
            <div class="flex items-center gap-2">
                <x-icon name="home" class="text-emerald-700" size="24" />
                <h2 class="text-xl font-bold text-gray-800">ประวัติการสมัคร</h2>
            </div>
            <p class="text-sm text-gray-500 -mt-2">รายการสมัครนิสิตดีเด่นทั้งหมดของคุณ</p>

            <div class="flex flex-col gap-y-4 mt-4 overflow-y-auto pr-2 custom-scrollbar">
                @forelse($registrations as $reg)
                    <div onclick=""
                         class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center hover:shadow-md transition-shadow group cursor-pointer">
                        <div class="flex items-center gap-5">
                            <div class="bg-gray-50 p-4 rounded-xl group-hover:bg-emerald-50 transition-colors">
                                @php
                                    $icon = match($reg->awardable_type) {
                                        'activity' => 'calendar',
                                        'innovation' => 'book',
                                        'behavior' => 'trophy',
                                        default => 'trophy'
                                    };
                                @endphp
                                <x-icon :name="$icon" class="text-emerald-700" size="28" />
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 text-lg">
                                    {{ $reg->award->title ?? 'รางวัลนิสิตดีเด่น' }}
                                    <span class="text-sm font-normal text-gray-500">
                                ({{ $reg->awardable_type === 'activity' ? 'ด้านกิจกรรมเสริมหลักสูตร' : ($reg->awardable_type === 'innovation' ? 'ด้านความคิดสร้างสรรค์และนวัตกรรม' : 'ด้านความประพฤติดี') }})
                            </span>
                                </h4>
                                <p class="text-gray-400 text-sm">
                                    {{ $reg->academic_year }} • สมัครเมื่อ {{ $reg->created_at->format('Y-m-d') }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            @php
                                $statusInfo = match($reg->status) {
                                    AwardRegistrationState::COMPLETED->value => ['bg' => 'bg-emerald-600', 'text' => 'เสร็จสมบูรณ์', 'icon' => 'book'],
                                    AwardRegistrationState::REJECTED->value => ['bg' => 'bg-red-600', 'text' => 'ไม่ผ่านการพิจารณา', 'icon' => 'book'],

                                    default => ['bg' => 'bg-orange-400', 'text' => 'กำลังดำเนินการ', 'icon' => 'book']
                                };
                            @endphp
                            <span class="{{ $statusInfo['bg'] }} text-white px-4 py-1.5 rounded-full text-sm flex items-center gap-1.5 shadow-sm">
                                <x-icon :name="$statusInfo['icon']" size="14" />
                                {{ $reg->status }}
                            </span>
                            <x-icon name="arrow-right" class="text-gray-800" />
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 bg-gray-50 rounded-2xl border border-dashed border-gray-300">
                        <p class="text-gray-500">คุณยังไม่มีประวัติการสมัครในขณะนี้</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $registrations->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
