<div class="space-y-6">
    <div class="space-y-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                รายงาน
            </label>
            <textarea
                name="report"
                rows="5"
                class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                placeholder="กรอกรายงาน">{{ old('report', session('award_registration.step2.report')) }}</textarea>
            @error('report')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
