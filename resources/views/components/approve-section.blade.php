<div class="flex flex-col rounded-xl border border-gray-300 bg-white shadow-sm">
    <div class="flex flex-col gap-1 border-b border-gray-300 p-5">
        <p class="font-bold">พิจารณาคำขอ</p>
        <p class="text-sm text-gray-400">กรุณาพิจารณาและให้ความเห็นชอบหรือไม่เห็นชอบคำขอนี้</p>
    </div>
    <div class="px-5 py-6">
        <form
            id="approval-form"
            method="POST"
            action="{{ route('approvals.store') }}"
            onsubmit="handleApprovalSubmit(event)"
        >
            @csrf
            <input type="hidden" name="application_id" value="{{ $application->id }}">
            <input type="hidden" name="user_id" value="{{ Auth::id() }}">
            <input type="hidden" id="status-input" name="status">

            <p>ความเห็นของกองพัฒนานิสิต</p>
            <textarea
                id="reason-textarea"
                rows="5"
                name="reason"
                placeholder="ระบุความเห็นเพิ่มเติม"
                required
                class="mt-4 w-full rounded-xl border border-gray-400 bg-background text-sm"
            ></textarea>
            <div class="mt-5 flex gap-3">
                <button
                    type="button"
                    onclick="submitApproval('APPROVED')"
                    id="approve-button"
                    class="flex flex-1 items-center justify-center gap-3 rounded-xl bg-primary p-3 text-white transition hover:opacity-70 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <x-icon name="check" class="stroke-white" />
                    <p>เห็นชอบ</p>
                </button>
                <button
                    type="button"
                    onclick="submitApproval('REJECTED')"
                    id="reject-button"
                    class="flex flex-1 items-center justify-center gap-3 rounded-xl bg-red-500 p-3 text-white transition hover:opacity-70 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <x-icon name="X" class="stroke-white" />
                    <p>ไม่เห็นชอบ</p>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function submitApproval(status) {
    const form = document.getElementById('approval-form');
    const statusInput = document.getElementById('status-input');
    const reasonTextarea = document.getElementById('reason-textarea');
    const approveButton = document.getElementById('approve-button');
    const rejectButton = document.getElementById('reject-button');

    // Validate reason field
    if (!reasonTextarea.value.trim()) {
        alert('กรุณาระบุความเห็นเพิ่มเติม');
        reasonTextarea.focus();
        return;
    }

    // Set status and disable buttons
    statusInput.value = status;
    approveButton.disabled = true;
    rejectButton.disabled = true;

    // Submit the form
    form.requestSubmit();
}
</script>
