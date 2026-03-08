@php
    $statusParts = explode('_', $application->status->value);
    $statusPrefix = $statusParts[0];
    $roleSuffix = count($statusParts) > 1 ? implode('_', array_slice($statusParts, 1)) : '';

    Log::info('LOG Application Data: ', $application->toArray());
@endphp
<x-app-layout>
    <div class="bg-gray-50 p-10 min-h-screen">
        <a href="{{ route('report.award-report') }}" class="mb-10 flex gap-2 items-center hover:opacity-70 transition">
            <x-icon name="arrow-left" />
            <p>กลับหน้าก่อนหน้า</p>
        </a>

        <div class="flex flex-col gap-2">
            <div class="flex items-center justify-start gap-3">
                <p class="text-2xl font-bold">{{ $application->id }}</p>
                @if ($application->status->value === \App\Enums\ApplicationStatus::REJECTED->value)
                    {{-- 1. ถ้าถูกปฏิเสธ ไม่ว่าจะเลเวลไหน ให้จบที่นี่ --}}
                    <div class="rounded-full border border-red-400 bg-red-100 px-3 py-1 text-red-500 text-sm">
                        ปฏิเสธ
                    </div>
                @elseif (
                    $application->status->value === \App\Enums\ApplicationStatus::APPROVED->value &&
                        $application->level->value === 5 &&
                        !$event)
                    {{-- 2. ถ้าอนุมัติเรียบร้อย (ถึงเลเวล 5 แล้ว) --}}
                    <div class="rounded-full border border-primary bg-green-50 px-3 py-1 text-primary text-sm">
                        อนุมัติ
                    </div>
                @else
                    {{-- 3. กรณีอื่นๆ (ยังไม่ถูก Reject และยังไม่ถึงขั้นอนุมัติสุดท้าย) --}}
                    <div class="rounded-full border border-amber-400 bg-amber-100 px-3 py-1 text-amber-500 text-sm">
                        รอพิจารณา
                    </div>
                @endif
            </div>
            <p class="text-gray-400">ยื่นเมื่อ {{ $application->created_at->translatedFormat('j F Y') }}</p>
        </div>

        <div class="mt-7 flex flex-col lg:flex-row gap-6">
            <div class="flex-[2] flex flex-col gap-6">
                <x-nisit-info :application="$application" />
                <x-application-preview :application="$application" />
                <x-document-section :application="$application" />
            </div>

            <div class="flex-1 flex flex-col gap-6">
                <x-progression :application="$application" :approvals="$approvals" :event="$event" />
            </div>
        </div>
    </div>
</x-app-layout>
