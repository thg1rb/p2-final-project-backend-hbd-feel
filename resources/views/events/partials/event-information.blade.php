@php
    $readonly = isset($viewMode) && $viewMode === 'show';
    $isEdit = isset($viewMode) && $viewMode === 'edit';
    $isCreate = isset($viewMode) && $viewMode === 'create';

    $formAction = $isCreate
        ? route('events.store')
        : route('events.update', $event);
@endphp

<section>
    <form method="post" action="{{ $formAction }}" class="p-10 flex flex-col gap-y-12 bg-white shadow-sm rounded-lg">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <div class="flex flex-col">
                <label for="name">ชื่อรอบการให้รางวัล <span class="text-red-500">*</span></label>
                <input
                    name="name"
                    type="text"
                    value="{{ old('name', $event?->name) }}"
                    {{ $readonly ? 'readonly' : '' }}
                    class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm {{ $readonly ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                    required
                >
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex flex-col">
                <label for="status">สถานะ <span class="text-red-500">*</span></label>
                <select
                    name="status"
                    {{ $readonly ? 'disabled' : '' }}
                    class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm {{ $readonly ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                    required
                >
                    <option value="">-- เลือกสถานะ --</option>
                    <option value="{{ \App\Enums\Status::OPENED->value }}" {{ old('status', $event?->status?->value) === \App\Enums\Status::OPENED->value ? 'selected' : '' }}>เปิดรอบการให้รางวัล</option>
                    <option value="{{ \App\Enums\Status::CLOSED->value }}" {{ old('status', $event?->status?->value) === \App\Enums\Status::CLOSED->value ? 'selected' : '' }}>ปิดรอบการให้รางวัล</option>
                </select>
                @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex flex-col">
                <label for="academic_year">ปีการศึกษา <span class="text-red-500">*</span></label>
                <select
                    id="academic_year"
                    name="academic_year"
                    {{ $readonly ? 'disabled' : '' }}
                    class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm {{ $readonly ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                    required
                >
                    <option value="">-- เลือกปีการศึกษา --</option>
                </select>
                @error('academic_year') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                @if(!$readonly)
                    <script>
                        (function() {
                            const AMOUNT_OF_YEAR = 5;
                            let currentYear = new Date().getFullYear() + 543;
                            const selectedYear = '{{ old('academic_year', $event?->academic_year) }}';
                            const select = document.getElementById('academic_year');

                            for (let i = 0; i < AMOUNT_OF_YEAR; i++) {
                                let option = document.createElement('option');
                                option.value = currentYear;
                                option.text = currentYear;
                                if (selectedYear && currentYear == selectedYear) {
                                    option.selected = true;
                                }
                                select.appendChild(option);
                                currentYear--;
                            }
                        })();
                    </script>
                @else
                    <script>
                        (function() {
                            const selectedYear = '{{ $event?->academic_year }}';
                            const select = document.getElementById('academic_year');
                            let option = document.createElement('option');
                            option.value = selectedYear;
                            option.text = selectedYear;
                            option.selected = true;
                            select.appendChild(option);
                        })();
                    </script>
                @endif
            </div>

            <div class="flex flex-col">
                <label for="semester">ภาคเรียน <span class="text-red-500">*</span></label>
                <select
                    id="semester"
                    name="semester"
                    {{ $readonly ? 'disabled' : '' }}
                    class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm {{ $readonly ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                    required
                >
                    <option value="">-- เลือกภาคเรียน --</option>
                    <option value="1" {{ old('semester', $event?->semester) == 1 ? 'selected' : '' }}>1</option>
                    <option value="2" {{ old('semester', $event?->semester) == 2 ? 'selected' : '' }}>2</option>
                </select>
                @error('semester') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex flex-col">
                <label for="start_date">วันที่เริ่มต้น <span class="text-red-500">*</span></label>
                <input
                    name="start_date"
                    type="date"
                    value="{{ old('start_date', $event?->start_date?->format('Y-m-d')) }}"
                    {{ $readonly ? 'readonly' : '' }}
                    class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm {{ $readonly ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                    required
                >
                @error('start_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex flex-col">
                <label for="end_date">วันที่สิ้นสุด <span class="text-red-500">*</span></label>
                <input
                    name="end_date"
                    type="date"
                    value="{{ old('end_date', $event?->end_date?->format('Y-m-d')) }}"
                    {{ $readonly ? 'readonly' : '' }}
                    class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm {{ $readonly ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                    required
                >
                @error('end_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        @if(!$readonly)
            <div class="flex flex-row justify-end items-center">
                <button type="submit" class="px-10 py-1.5 flex-2 bg-primary font-semibold text-white text-[18px] rounded-md">ยืนยัน</button>
            </div>
        @endif
    </form>
</section>
