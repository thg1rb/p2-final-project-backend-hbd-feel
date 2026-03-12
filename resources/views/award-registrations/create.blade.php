<x-app-layout>
    <div class="max-w-5xl mx-auto px-6 pt-12 pb-8">
        <div class="bg-white rounded-xl shadow border border-gray-100 p-8">
            <a href="{{ route('award-registrations') }}"
               class="text-sm text-gray-500 hover:text-gray-700 flex items-center mb-6">
                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 19l-7-7 7-7" />
                </svg>
                กลับไปแดชบอร์ด
            </a>
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">สมัครนิสิตดีเด่น</h1>
                <p class="text-gray-500">ภาคเรียนที่ 2/2567</p>
            </div>
            <div class="flex items-center gap-6 mb-10">
                @foreach ([1 => 'เลือกประเภท', 2 => 'กรอกข้อมูล', 3 => 'ยืนยัน'] as $i => $label)
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold
                            {{ $step === $i
                                ? 'bg-emerald-600 text-white'
                                : ($step > $i
                                    ? 'bg-emerald-100 text-emerald-700'
                                    : 'bg-gray-100 text-gray-400 border') }}">
                            {{ $i }}
                        </div>
                        <span class="text-sm
                            {{ $step === $i ? 'text-emerald-700 font-medium' : 'text-gray-400' }}">
                            {{ $label }}
                        </span>
                    </div>
                    @if ($i < 3)
                        <div class="flex-1 h-px bg-gray-200"></div>
                    @endif
                @endforeach
            </div>

            <hr class="mb-8">
            @yield('content')

        </div>
    </div>
</x-app-layout>
