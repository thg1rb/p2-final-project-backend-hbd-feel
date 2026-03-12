<x-app-layout>
    <div class="bg-gray-50 p-10 min-h-screen">
        <a href="{{ route('report.show', $application) }}"
            class="mb-10 flex gap-2 items-center hover:opacity-70 transition">
            <x-icon name="arrow-left" />
            <p>กลับหน้าก่อนหน้า</p>
        </a>
        <div class="flex flex-col gap-4">
            <p class="text-2xl font-bold">แก้ไขประเภทรางวัล</p>
            <div class="flex gap-3 items-center border-b border-b-gray-400 pb-10">
                <p class="text-sm">ประเภทรางวัลเดิม: </p>
                <div
                    class="flex w-fit items-center justify-center gap-1 rounded-full border border-blue-500 bg-blue-50 px-2 py-1 text-sm text-blue-500">
                    <x-icon name="badge" class="w-4 h-4" />
                    <p>{{ $application->award->name }}</p>
                </div>
            </div>
            <div class="flex flex-col gap-3">
                <p class="font-semibold">ระบุประเภทรางวัลใหม่ที่ต้องการแก้ไข</p>
                <form action="{{ route('report.update', $application) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <select name="award_id" id="award_id"
                        class="rounded-xl w-full border border-gray-400 cursor-pointer focus:border-primary focus:outline-none focus:ring-primary text-sm">
                        <option value="" disabled selected>-- เลือกประเภทรางวัล --</option>
                        @foreach ($awards as $award)
                            @if ($award->campus == $application->user->campus)
                                <option value="{{ $award->id }}">
                                    {{ $award->name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    <div class="flex justify-end items-center gap-5 mt-5">
                        <button type="button" class="rounded-xl border border-gray-400 py-2 px-10 active:opacity-50">
                            ยกเลิก
                        </button>
                        <button type="submit" class="text-white rounded-xl py-2 px-10 bg-primary active:bg-primary/50">
                            ตกลง
                        </button>
                    </div>
                </form>
            </div>
        </div>

</x-app-layout>
