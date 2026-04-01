<script>
    document.addEventListener("DOMContentLoaded", function () {

        const input = document.getElementById("application_document");
        const previewContainer = document.getElementById("preview-container");
        const preview = document.getElementById("preview");

        const fileInfo = document.getElementById("file-info");
        const fileLink = document.getElementById("file-link");

        const removeExistingInput = document.getElementById("remove_existing_file");

        const existingFile = previewContainer.dataset.existingFile;
        const existingName = previewContainer.dataset.existingName;

        if (existingFile) {

            preview.src = existingFile;

            previewContainer.classList.remove("hidden");
            previewContainer.classList.add("flex");

            fileLink.textContent = existingName;
            fileLink.href = existingFile;

            fileInfo.classList.remove("hidden");

        }

        input.addEventListener("change", function(event) {

            const file = event.target.files[0];
            if (!file) return;

            if (file.type !== "application/pdf") {

                alert("รองรับเฉพาะไฟล์ PDF เท่านั้น");
                input.value = "";
                return;

            }

            const MAX_SIZE = 10 * 1024 * 1024;

            if (file.size > MAX_SIZE) {

                alert("ไฟล์ต้องมีขนาดไม่เกิน 10MB");
                input.value = "";
                return;

            }


            const url = URL.createObjectURL(file);

            preview.src = url;

            previewContainer.classList.remove("hidden");
            previewContainer.classList.add("flex");

            const sizeMB = (file.size / (1024 * 1024)).toFixed(2);

            fileLink.textContent = `${file.name} (${sizeMB} MB)`;
            fileLink.href = url;

            fileInfo.classList.remove("hidden");

            removeExistingInput.value = "0";

        });
    });
</script>


<x-app-layout>
    <div class="p-10 flex flex-col gap-y-7">
        <a class="flex gap-2" href="{{ url()->previous() }}">
            <x-icon name="arrow-left"></x-icon>
            <p>กลับหน้าจัดการหมวดรางวัล</p>
        </a>

        {{-- Header --}}
        <div class="flex flex-col justify-center items-center">
            <h1 class="font-bold text-[32px]">รายละเอียดหมวดรางวัล</h1>
        </div>
        <form
            action="{{route('awards.update', $award)}}"
            method="POST"
            enctype="multipart/form-data"
            class="flex items-center justify-center">
            @csrf
            @method('PUT')
            <div class="flex flex-col justify-start items-start w-1/2 bg-white p-5 rounded-xl gap-5">
                <div class="w-full">
                    <label for="name" class="block text-sm font-medium text-gray-700">
                        ชื่อหมวดรางวัล  <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" disabled placeholder="เช่น รางวัลเรียนดี..." value="{{ old('name', $award->name) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm"
                    />
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                @php
                    $existingFileUrl = $award->form_path
                        ? route('file.preview', ['path' => $award->form_path])
                        : null;
                @endphp
                <div id="preview-container" class="hidden flex-col gap-2 w-full"
                     data-existing-file="{{ $existingFileUrl }}"
                     data-existing-name="{{ basename($award->form_path ?? '') }}"
                >
                    <p class="text-sm font-medium text-gray-700">ไฟล์ใบสมัคร</p>
                    <div id="file-info" class="hidden flex items-center gap-3 text-sm mt-2">
                        <a id="file-link"
                           target="_blank"
                           class="text-emerald-700 font-medium underline hover:text-emerald-900">
                        </a>
                    </div>

                    <div class="w-full h-[500px] border rounded-xl overflow-hidden shadow-sm bg-gray-100">
                        <iframe id="preview" class="w-full h-full"></iframe>
                    </div>
                </div>
                <div class="w-full flex flex-col gap-4">
                    <label class="text-sm font-medium text-gray-700">
                        เอกสารเพิ่มเติม
                    </label>

                    <div id="dynamic-fields" class="flex flex-col gap-3">
                        @if(!empty($award->requirements))
                            @foreach($award->requirements as $index => $field)
                                <div class="field-row flex flex-col gap-2 p-4 rounded-lg border bg-gray-50">

                                    <input type="hidden"
                                           name="requirements[{{ $index }}][id]"
                                           value="{{ $field['id'] }}">

                                    <input
                                        type="text"
                                        name="requirements[{{ $index }}][name]"
                                        value="{{ $field['name'] }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm text-sm"
                                        disabled
                                    >

                                    <div class="flex items-center justify-between">

                                        <label class="flex items-center gap-2 text-sm text-gray-600">

                                            <input type="hidden"
                                                   name="requirements[{{ $index }}][required]"
                                                   value="0">

                                            <input
                                                type="checkbox"
                                                name="requirements[{{ $index }}][required]"
                                                value="1"
                                                {{ $field['required'] ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-emerald-600"
                                                disabled
                                            >

                                            จำเป็นต้องอัปโหลด
                                        </label>


                                    </div>

                                </div>
                            @endforeach
                        @endif
                    </div>

                </div>

            </div>
        </form>
    </div>
</x-app-layout>
