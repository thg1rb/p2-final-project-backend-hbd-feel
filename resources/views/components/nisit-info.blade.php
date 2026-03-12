@props(['application'])
<div class="flex flex-col gap-4 rounded-xl border border-gray-300 bg-white p-7 shadow-sm">
    <div class="flex gap-3 font-bold border-b pb-4">
        <x-icon name="user" class="text-blue-600" />
        <p>ข้อมูลนิสิต</p>
    </div>
    <div class="grid grid-cols-2 gap-y-6">
        <div class="flex flex-col gap-2">
            <p class="text-sm text-gray-400">ชื่อ-นามสกุล</p>
            <p class="font-medium">{{ $application->user->firstName }} {{ $application->user->lastName }}</p>
        </div>
        <div class="flex flex-col gap-2">
            <p class="text-sm text-gray-400">รหัสนิสิต</p>
            <p class="font-medium">{{ $application->user->student_id }}</p>
        </div>
        <div class="flex flex-col gap-2">
            <p class="text-sm text-gray-400">หลักสูตร</p>
            <p class="font-medium">{{ $application->user->department->name ?? '-' }}</p>
        </div>
        <div class="flex flex-col gap-2">
            <p class="text-sm text-gray-400">ชั้นปี</p>
            <p class="font-medium">ปีที่ {{ $application->year }}</p>
        </div>
        {{-- Row 3 --}}
        <div class="flex flex-col gap-2">
            <p class="text-sm text-gray-400">เกรดเฉลี่ยสะสม</p>
            <p class="font-medium">{{ number_format($application->grade, 2) }}</p>
        </div>
        <div class="flex flex-col gap-2">
            <p class="text-sm text-gray-400">ประเภทรางวัลที่เสนอ</p>
            <div class="flex gap-3 items-center">
                <div
                    class="flex w-fit items-center justify-center gap-1 rounded-full border border-blue-500 bg-blue-50 px-2 py-1 text-sm text-blue-500">
                    <x-icon name="badge" class="w-4 h-4" />
                    <p>{{ $application->award->name }}</p>
                </div>
                <a href="{{ route('report.edit', $application->id) }}"
                    class="text-sm text-blue-400 underline cursor-pointer">แก้ไขประเภทรางวัล</a>
            </div>
        </div>
    </div>
</div>
