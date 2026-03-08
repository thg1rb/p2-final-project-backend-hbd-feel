<script>
    document.addEventListener("DOMContentLoaded", function () {
        const input = document.getElementById("application_document");
        const previewContainer = document.getElementById("preview-container");
        const preview = document.getElementById("preview");

        const fileInfo = document.getElementById("file-info");
        const fileLink = document.getElementById("file-link");
        const removeBtn = document.getElementById("remove-file");


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

        removeBtn.addEventListener("click", function () {

            // clear input
            input.value = "";

            // remove preview
            preview.src = "";
            preview.classList.add("hidden");
            previewContainer.classList.add("hidden");

            // hide file info
            fileInfo.classList.add("hidden");

            // revoke memory
            if (currentObjectURL) {
                URL.revokeObjectURL(currentObjectURL);
                currentObjectURL = null;
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
                        อัปโหลดไฟล์สมัคร <span class="text-red-500">*</span>
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

                        <button type="button"
                                id="remove-file"
                                class="text-red-500 hover:text-red-700 text-xs">
                            ลบไฟล์
                        </button>
                    </div>

                    <div class="w-full h-[500px] border rounded-xl overflow-hidden shadow-sm bg-gray-100">
                        <iframe id="preview" class="w-full h-full"></iframe>
                    </div>
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
