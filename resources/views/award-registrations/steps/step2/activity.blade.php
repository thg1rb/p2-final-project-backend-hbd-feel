<div class="space-y-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            ผลงานและความสำเร็จ <span class="text-red-500">*</span>
        </label>
        <textarea
            name="achievement"
            rows="4"
            class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
            placeholder="อธิบายผลงานและความสำเร็จของคุณ">{{ old('achievement', session('award_registration.step2.achievement')) }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            จำนวนชั่วโมงกิจกรรม
        </label>
        <input
            type="number"
            name="activity_hours"
            min="0"
            value="{{ old('activity_hours', session('award_registration.step2.activity_hours')) }}"
            class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
            placeholder="เช่น 100"
        >
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            บทบาทหรือหน้าที่ที่ได้รับ
        </label>
        <input
            type="text"
            name="role"
            value="{{ old('role', session('award_registration.step2.role')) }}"
            class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
            placeholder="เช่น ประธานโครงการ / ผู้ประสานงาน">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            ข้อมูลเพิ่มเติม
        </label>
        <textarea
            name="additional_info"
            rows="3"
            class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
            placeholder="ข้อมูลอื่น ๆ (ถ้ามี)">{{ old('additional_info', session('award_registration.step2.additional_info')) }}</textarea>
    </div>

</div>
