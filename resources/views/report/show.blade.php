@php
    use App\Enums\RoleLevel;
    use App\Enums\ApprovalStatus;

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
                @if ($application->status->value === ApprovalStatus::REJECTED->value)
                    <div
                        class="rounded-full border border-red-400 bg-red-100 px-3 py-1 text-red-500 text-sm w-fit">
                        ปฏิเสธ
                    </div>
                @elseif ($application->level->value === RoleLevel::BOARD->value && !$event)
                    <div
                        class="rounded-full border border-primary bg-green-50 px-3 py-1 text-primary text-sm w-fit">
                        อนุมัติ
                    </div>
                @elseif ($application->status->value === ApprovalStatus::APPROVED->value && $application->level->value === RoleLevel::DEAN->value)
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
                @if ($application->level === RoleLevel::DEAN && auth()->user()->role === \App\Enums\UserRole::NISIT_DEV)
                <x-approve-section :application="$application" />
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
