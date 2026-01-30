@php
    $documents = session('award_registration.step2.documents', []);
@endphp

<div class="space-y-6">
    <div class="space-y-6">
        <p class="text-gray-500 text-sm">
            ต้องได้รับรางวัลจากการประกวดหรือการแข่งขันในระดับอุดมศึกษา ระดับชาติ หรือระดับนานาชาติ
            ซึ่งจัดโดยหน่วยงานภาครัฐหรือภาคเอกชน
        </p>

        <div class="space-y-4">
            <label class="block text-sm font-medium text-gray-700">
                รายละเอียดผลงาน / ความดีเด่น
            </label>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <input
                        type="date"
                        name="award_date"
                        value="{{ old('award_date', session('award_registration.step2.award_date')) }}"
                        class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                    @error('award_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <input
                        type="text"
                        name="project_name"
                        value="{{ old('project_name', session('award_registration.step2.project_name')) }}"
                        class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                        placeholder="โครงการ / รายการที่เข้าร่วม">
                    @error('project_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <input
                        type="text"
                        name="team_name"
                        value="{{ old('team_name', session('award_registration.step2.team_name')) }}"
                        class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                        placeholder="ชื่อทีม">
                    @error('team_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <input
                        type="text"
                        name="work_name"
                        value="{{ old('work_name', session('award_registration.step2.work_name')) }}"
                        class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                        placeholder="ชื่อผลงาน">
                    @error('work_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <input
                        type="text"
                        name="award_name"
                        value="{{ old('award_name', session('award_registration.step2.award_name')) }}"
                        class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                        placeholder="รางวัลที่ได้รับ">
                    @error('award_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <input
                        type="text"
                        name="organizer"
                        value="{{ old('organizer', session('award_registration.step2.organizer')) }}"
                        class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                        placeholder="หน่วยงานผู้จัด">
                    @error('organizer')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                อัปโหลดเอกสารหลักฐาน
            </label>

            <label
                for="documents"
                class="flex flex-col items-center justify-center gap-2
                   w-full h-48 cursor-pointer
                   rounded-xl border-2 border-dashed border-gray-300
                   bg-gray-50 text-gray-600
                   hover:border-emerald-500 hover:bg-emerald-50
                   transition">
                <svg class="h-10 w-10 text-emerald-600" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12.5535 2.49392C12.4114 2.33852 12.2106 2.25 12 2.25C11.7894 2.25 11.5886 2.33852 11.4465 2.49392L7.44648 6.86892C7.16698 7.17462 7.18822 7.64902 7.49392 7.92852C7.79963 8.20802 8.27402 8.18678 8.55352 7.88108L11.25 4.9318V16C11.25 16.4142 11.5858 16.75 12 16.75C12.4142 16.75 12.75 16.4142 12.75 16V4.9318L15.4465 7.88108C15.726 8.18678 16.2004 8.20802 16.5061 7.92852C16.8118 7.64902 16.833 7.17462 16.5535 6.86892L12.5535 2.49392Z" fill="#1C274C"/>
                    <path d="M3.75 15C3.75 14.5858 3.41422 14.25 3 14.25C2.58579 14.25 2.25 14.5858 2.25 15V15.0549C2.24998 16.4225 2.24996 17.5248 2.36652 18.3918C2.48754 19.2919 2.74643 20.0497 3.34835 20.6516C3.95027 21.2536 4.70814 21.5125 5.60825 21.6335C6.47522 21.75 7.57754 21.75 8.94513 21.75H15.0549C16.4225 21.75 17.5248 21.75 18.3918 21.6335C19.2919 21.5125 20.0497 21.2536 20.6517 20.6516C21.2536 20.0497 21.5125 19.2919 21.6335 18.3918C21.75 17.5248 21.75 16.4225 21.75 15.0549V15C21.75 14.5858 21.4142 14.25 21 14.25C20.5858 14.25 20.25 14.5858 20.25 15C20.25 16.4354 20.2484 17.4365 20.1469 18.1919C20.0482 18.9257 19.8678 19.3142 19.591 19.591C19.3142 19.8678 18.9257 20.0482 18.1919 20.1469C17.4365 20.2484 16.4354 20.25 15 20.25H9C7.56459 20.25 6.56347 20.2484 5.80812 20.1469C5.07435 20.0482 4.68577 19.8678 4.40901 19.591C4.13225 19.3142 3.9518 18.9257 3.85315 18.1919C3.75159 17.4365 3.75 16.4354 3.75 15Z" fill="#1C274C"/>
                </svg>

                <p class="text-sm font-medium">
                    คลิกเพื่ออัปโหลดไฟล์
                </p>
                <p class="text-xs text-gray-500">
                    รองรับ PDF, รูปภาพ, Word (สูงสุด 10MB ต่อไฟล์)
                </p>

                <input
                    type="file"
                    id="documents"
                    name="documents[]"
                    multiple
                    class="hidden">
            </label>
        </div>

        <ul id="file-preview" class="mt-3 text-sm text-gray-600 list-disc list-inside">
            @foreach ($documents as $path)
                <li>{{ basename($path) }}</li>
            @endforeach
        </ul>
    </div>
</div>
