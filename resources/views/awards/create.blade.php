<script>
    document.addEventListener("DOMContentLoaded", function () {
        const input = document.getElementById("application_document");
        const previewContainer = document.getElementById("preview-container");
        const preview = document.getElementById("preview");

        const fileInfo = document.getElementById("file-info");
        const fileLink = document.getElementById("file-link");


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
            preview.style.display = "block";

            const sizeMB = (file.size / (1024 * 1024)).toFixed(2);

            fileLink.textContent = `${file.name} (${sizeMB} MB)`;
            fileLink.href = url;

            fileInfo.classList.remove("hidden");
        });

        const container = document.getElementById("dynamic-fields");
        const addBtn = document.getElementById("add-field");

        let index = {{ isset($award) && $award->extra_fields ? count($award->extra_fields) : 1 }};

        function createField() {

            const id = `req_${String(index).padStart(3, '0')}`;

            const wrapper = document.createElement("div");
            wrapper.className = `
                field-row
                flex flex-col gap-2
                p-4 rounded-lg border
                bg-gray-50
            `;

        wrapper.innerHTML = `
        <input type="hidden" name="requirements[${index}][id]" value="${id}">

        <input
            type="text"
            name="requirements[${index}][name]"
            placeholder="เช่น Transcript หรือ สำเนาบัตรนิสิต"
            class="w-full rounded-md border-gray-300 shadow-sm text-sm
                   focus:ring-emerald-500 focus:border-emerald-500"
        >

        <div class="flex items-center justify-between">

            <label class="flex items-center gap-2 text-sm text-gray-600">
                <input type="hidden" name="requirements[${index}][required]" value="0">

                <input
                    type="checkbox"
                    name="requirements[${index}][required]"
                    value="1"
                    class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                >

                จำเป็นต้องอัปโหลด
            </label>

            <button
                type="button"
                class="remove-field text-sm text-red-500 hover:text-red-700">
                ลบ
            </button>

        </div>
    `;

            container.appendChild(wrapper);
            index++;
        }

        addBtn.addEventListener("click", createField);

        container.addEventListener("click", (e) => {
            if (e.target.classList.contains("remove-field")) {
                e.target.closest(".field-row").remove();
            }
        });
    });


</script>

<x-app-layout>
    <div class="p-10 flex flex-col gap-y-7">
        <a class="flex gap-2" href="{{ route('awards.index') }}">
            <x-icon name="arrow-left"></x-icon>
            <p>กลับหน้าจัดการหมวดรางวัล</p>
        </a>

        {{-- Header --}}
        <div class="flex flex-col justify-center items-center">
            <h1 class="font-bold text-[32px]">เพิ่มหมวดรางวัล</h1>
        </div>
        <form
            action="{{route('awards.store')}}"
            method="POST"
            enctype="multipart/form-data"
            class="flex items-center justify-center">
            @csrf
            <div class="flex flex-col justify-start items-start w-1/2 bg-white p-5 rounded-xl gap-5">
                <div class="w-full">
                    <label for="name" class="block text-sm font-medium text-gray-700">
                        ชื่อหมวดรางวัล  <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" placeholder="เช่น รางวัลเรียนดี..." value="{{ old('name') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm"
                    />
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
{{--                <div>--}}
{{--                    <label for="reward" class="block text-sm font-medium text-gray-700">--}}
{{--                        จำนวนเงินรางวัล  <span class="text-red-500">*</span>--}}
{{--                    </label>--}}
{{--                    <input type="text" name="reward" id="reward" placeholder="1000" value="{{ old('reward') }}"--}}
{{--                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm"--}}
{{--                    />--}}
{{--                    @error('reward') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror--}}
{{--                </div>--}}
                <div class="flex flex-col w-full">
                    <p class="block text-sm font-medium text-gray-700 mb-2">
                        อัปโหลดไฟล์ใบสมัคร <span class="text-red-500">*</span>
                    </p>
                    <label for="application_document" class="flex flex-col items-center justify-center gap-2 w-full h-48 cursor-pointer rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 text-gray-600 p-4 hover:border-emerald-500 hover:bg-emerald-50 transition">
                        <p class="text-sm font-medium">คลิกเพื่ออัปโหลดไฟล์</p>
                        <p class="text-xs text-gray-500">
                            รองรับเฉพาะไฟล์ PDF (สูงสุด 10MB ต่อไฟล์)
                        </p>
                    </label>
                    <input type="file" name="application_document" id="application_document" class="hidden" accept="application/pdf">
                    @error('application_document') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div id="preview-container" class="hidden flex-col gap-2 w-full">
                    <p class="text-sm font-medium text-gray-700">ตัวอย่างไฟล์</p>
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

                    <div id="dynamic-fields" class="flex flex-col gap-3"></div>

                    <button
                        type="button"
                        id="add-field"
                        class="flex items-center gap-2 w-fit px-2 py-1 text-sm font-medium
               bg-emerald-50 text-emerald-700 rounded-lg
               hover:bg-emerald-100 transition">

                        <span class="text-lg">＋</span>
                        เพิ่มช่องเอกสารเพิ่มเติม
                    </button>
                </div>
                <button
                    class="py-2 px-5 rounded text-center text-white bg-[#226e64] hover:scale-105 cursor-pointer transition-all"
                    type="submit"
                >
                    เพิ่มหมวดรางวัล
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
