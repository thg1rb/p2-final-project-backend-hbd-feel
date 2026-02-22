@php
    $statusParts = explode('_', $application->status->value);
    $statusPrefix = $statusParts[0];
    $roleSuffix = count($statusParts) > 1 ? implode('_', array_slice($statusParts, 1)) : '';
@endphp
<x-app-layout>
    <div class="bg-gray-50 p-10 min-h-screen">
        <button type="button" class="mb-10 flex gap-2 items-center hover:opacity-70 transition" onclick="history.back()">
            <x-icon name="arrow-left" />
            <p>กลับหน้าก่อนหน้า</p>
        </button>

        <div class="flex flex-col gap-2">
            <div class="flex items-center justify-start gap-3">
                <p class="text-2xl font-bold">{{ $application->id }}</p>

                @if ($application->status === \App\Enums\ApplicationStatus::SUBMITTED)
                    <div class="rounded-full border border-amber-400 bg-amber-100 px-3 py-1 text-amber-500 text-sm">
                        รอพิจารณา
                    </div>
                @elseif ($roleSuffix !== 'DEPT_HEAD')
                    <div class="rounded-full border border-blue-500 bg-blue-100 px-3 py-1 text-blue-600 text-sm">
                        อนุมัติ
                    </div>
                @elseif ($roleSuffix === 'DEPT_HEAD' && $statusPrefix === 'APPROVED')
                    <div class="rounded-full border border-blue-500 bg-blue-50 px-3 py-1 text-blue-600 text-sm">
                        อนุมัติ
                    </div>
                @elseif ($roleSuffix === 'DEPT_HEAD' && $statusPrefix === 'REJECTED')
                    <div class="rounded-full border border-red-400 bg-red-100 px-3 py-1 text-red-500 text-sm">
                        ปฏิเสธ
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
                <x-progression :application="$application" :approvals="$approvals" />
            </div>
        </div>
    </div>
</x-app-layout>
