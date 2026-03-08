<div
    x-data="{
        isSubmitting: false,
        form: {
            status: '',
            reason: '',
            application_id: '{{ $application->id }}',
            user_id: '{{ Auth::id() }}'
        },
        handleSubmit(event, status) {
            event.preventDefault();
            this.form.status = status;

            const textarea = document.querySelector('textarea[name=reason]');
            if (!textarea.value.trim()) {
                alert('กรุณาระบุความเห็นเพิ่มเติม');
                return;
            }

            this.isSubmitting = true;

            const form = event.target.closest('form');
            if (form) {
                form.submit();
            } else {
                console.error('Form not found');
            }
        }
    }"
    class="flex flex-col rounded-xl border border-gray-300 bg-white shadow-sm"
>
    <div class="flex flex-col gap-1 border-b border-gray-300 p-5">
        <p class="font-bold">พิจารณาคำขอ</p>
        <p class="text-sm text-gray-400">กรุณาพิจารณาและให้ความเห็นชอบหรือไม่เห็นชอบคำขอนี้</p>
    </div>
    <div class="px-5 py-6">
        <form method="POST" action="{{ route('approvals.store') }}">
            @csrf
            <input type="hidden" name="application_id" :value="form.application_id">
            <input type="hidden" name="user_id" :value="form.user_id">
            <input type="hidden" name="status" :value="form.status">

            <p>ความเห็นของกองพัฒนานิสิต</p>
            <textarea
                rows="5"
                name="reason"
                placeholder="ระบุความเห็นเพิ่มเติม"
                required
                x-model="form.reason"
                class="mt-4 w-full rounded-xl border border-gray-400 bg-background text-sm"
                :disabled="isSubmitting"
            ></textarea>
            <div class="mt-5 flex gap-3">
                <button
                    type="button"
                    @click="handleSubmit($event, 'APPROVED')"
                    :disabled="isSubmitting"
                    class="flex flex-1 items-center justify-center gap-3 rounded-xl bg-primary p-3 text-white transition hover:opacity-70 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <x-icon name="check" class="stroke-white" />
                    <p>เห็นชอบ</p>
                </button>
                <button
                    type="button"
                    @click="handleSubmit($event, 'REJECTED')"
                    :disabled="isSubmitting"
                    class="flex flex-1 items-center justify-center gap-3 rounded-xl bg-red-500 p-3 text-white transition hover:opacity-70 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <x-icon name="X" class="stroke-white" />
                    <p>ไม่เห็นชอบ</p>
                </button>
            </div>
        </form>
    </div>
</div>
