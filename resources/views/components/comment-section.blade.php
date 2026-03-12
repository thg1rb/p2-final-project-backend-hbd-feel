@props(['headDeptApproval'])
<div class="flex flex-col gap-4 rounded-xl border border-gray-300 bg-white p-7 shadow-sm">
    <div class="flex gap-3 font-bold">
        <x-icon name="comment" class="text-blue-600" />
        <p>ความคิดเห็นหัวหน้าภาควิชา</p>
    </div>
    @if ($headDeptApproval)
        <div class="rounded-2xl bg-gray-100 p-5">
            <p class="font-medium text-blue-600">{{ $headDeptApproval->user->firstName }}
                {{ $headDeptApproval->user->lastName }}</p>
            <p class="mt-2 text-sm text-gray-600 italic">
                {{ $headDeptApproval->reason ?? 'ไม่มีความเห็นเพิ่มเติม' }}
            </p>
        </div>
    @else
        <p class="text-gray-400 text-sm">ยังไม่มีความคิดเห็น</p>
    @endif
</div>
