@extends('award-registrations.create')

@section('content')
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-1">เลือกประเภทรางวัล</h3>
        <p class="text-sm text-gray-500">
            เลือกประเภทรางวัลที่ตรงกับความสามารถและผลงานของคุณ (เลือกได้ 1 ประเภท)
        </p>
    </div>

    <form method="POST" action="{{ route('award-registrations.store') }}?step={{ $step }}">
        @csrf
        <div class="space-y-4">
            <label class="flex items-center gap-4 p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                <input
                    type="radio"
                    name="award_type"
                    value="activity"
                    class="text-emerald-600"
                    @checked(
                        old('award_type', session('award_registration.step1.award_type')) === 'activity'
                    )>
                <div>
                    <p class="font-medium text-gray-900">ด้านกิจกรรมเสริมหลักสูตร</p>
                    <p class="text-sm text-gray-500">มีผลงานกิจกรรมเสริมหลักสูตรดีเด่น</p>
                </div>
            </label>
            <label class="flex items-center gap-4 p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                <input
                    type="radio"
                    name="award_type"
                    value="innovation"
                    class="text-emerald-600"
                    @checked(
                        old('award_type', session('award_registration.step1.award_type')) === 'innovation'
                    )>
                <div>
                    <p class="font-medium text-gray-900">ด้านความคิดสร้างสรรค์และนวัตกรรม</p>
                    <p class="text-sm text-gray-500">มีผลงานด้านนวัตกรรมโดดเด่น</p>
                </div>
            </label>
            <label class="flex items-center gap-4 p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                <input
                    type="radio"
                    name="award_type"
                    value="good_conduct"
                    class="text-emerald-600"
                    @checked(
                        old('award_type', session('award_registration.step1.award_type')) === 'good_conduct'
                    )>
                <div>
                    <p class="font-medium text-gray-900">ด้านความประพฤติดี</p>
                    <p class="text-sm text-gray-500">มีความประพฤติดีเป็นแบบอย่าง</p>
                </div>
            </label>
        </div>


        <div class="mt-10 flex justify-end">
            <button class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg">
                ถัดไป >
            </button>
        </div>
    </form>
@endsection
