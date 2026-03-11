@php
    $readonly = isset($viewMode) && $viewMode === 'show';
    $isEdit = isset($viewMode) && $viewMode === 'edit';
    $isCreate = isset($viewMode) && $viewMode === 'create';

    $formAction = $isCreate
        ? route('events.store')
        : route('events.update', $event);
@endphp

@if(!$readonly)
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>
    <script>
        const flatpickrConfig = {
            locale: "th",
            dateFormat: "Y-m-d", // Saves as AD (e.g., 2024-01-01)
            altInput: true,
            altFormat: "d F Y",
            minDate: {{ $isCreate ? '"today"' : 'null' }},

            onReady: function(selectedDates, dateStr, instance) {
                updateInputField(instance);
                drawBuddhistYear(instance);
            },

            onOpen: function(selectedDates, dateStr, instance) {
                drawBuddhistYear(instance);
            },

            onMonthChange: function(selectedDates, dateStr, instance) {
                drawBuddhistYear(instance);
            },

            onYearChange: function(selectedDates, dateStr, instance) {
                drawBuddhistYear(instance);
            },

            onChange: function(selectedDates, dateStr, instance) {
                updateInputField(instance);
            }
        };

        // --- Helper Functions ---

        // Logic to update the "Alt Input" (The text box user sees)
        function updateInputField(instance) {
            if (instance.selectedDates.length > 0) {
                const date = instance.selectedDates[0];
                const beYear = date.getFullYear() + 543;
                const formatted = instance.formatDate(date, "d F");
                instance.altInput.value = `${formatted} ${beYear}`;
            }
        }

        // Logic to update the Calendar Popup Header (The box inside the calendar)
        function drawBuddhistYear(instance) {
            setTimeout(() => {
                if (instance.currentYearElement) {
                    // Calculate BE Year
                    const beYear = instance.currentYear + 543;
                    // Force the input value to show BE
                    instance.currentYearElement.value = beYear;
                }
            }, 10);
        }

        // Initialize both date pickers
        document.addEventListener('DOMContentLoaded', function() {
            const startInput = document.getElementById("start_date");
            const endInput = document.getElementById("end_date");

            flatpickr("#start_date", {
                ...flatpickrConfig,
                defaultDate: startInput.value || null,
            });

            flatpickr("#end_date", {
                ...flatpickrConfig,
                defaultDate: endInput.value || null,
            });
        });
    </script>
@endif

<section>
    <form method="post" action="{{ $formAction }}" class="p-10 flex flex-col gap-y-12 bg-white shadow-sm rounded-lg">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <div class="flex flex-col md:col-span-2">
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

            <input type="hidden" name="campus" value="{{ old('campus', auth()->user()->campus) }}">
            @error('campus') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

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
                        (function () {
                            const RANGE = 6;
                            const currentYear = new Date().getFullYear() + 543;
                            const selectedYear = '{{ old('academic_year', $event?->academic_year) }}';
                            const select = document.getElementById('academic_year');

                            const startYear = currentYear + RANGE;
                            const endYear = currentYear - RANGE;

                            for (let year = startYear; year >= endYear; year--) {
                                const option = document.createElement('option');
                                option.value = year;
                                option.text = year;

                                if (selectedYear && year == selectedYear) {
                                    option.selected = true;
                                }

                                select.appendChild(option);
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
                    id="start_date"
                    name="start_date"
                    type="text"
                    value="{{
                        $readonly && $event?->start_date
                        ? $event->start_date->locale('th')->translatedFormat('d F') . ' ' . ($event->start_date->year + 543)
                        : old('start_date', $event?->start_date?->format('Y-m-d'))
                    }}"
                    {{ $readonly ? 'disabled' : '' }}
                    class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm {{ $readonly ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                    required
                >
                @error('start_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex flex-col">
                <label for="end_date">วันที่สิ้นสุด <span class="text-red-500">*</span></label>
                <input
                    id="end_date"
                    name="end_date"
                    type="text"
                    value="{{
                        $readonly && $event?->end_date
                        ? $event->end_date->locale('th')->translatedFormat('d F') . ' ' . ($event->end_date->year + 543)
                        : old('end_date', $event?->end_date?->format('Y-m-d'))
                    }}"
                    {{ $readonly ? 'disabled' : '' }}
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