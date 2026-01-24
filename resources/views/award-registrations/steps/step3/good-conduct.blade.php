    @php
        $step1 = session('award_registration.step1');
        $step2 = session('award_registration.step2');
    @endphp

    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-1">
            ตรวจสอบและยืนยัน
        </h3>
        <p class="text-sm text-gray-500">
            กรุณาตรวจสอบข้อมูลให้ถูกต้องก่อนส่งใบสมัคร
        </p>
    </div>

    <div class="bg-gray-50 border rounded-lg p-6 space-y-4 mb-6">
        <div>
            <p class="text-sm text-gray-500">ประเภทรางวัล</p>
            <p class="font-medium text-gray-900">
                @switch($step1['award_type'])
                    @case('activity')
                        ด้านกิจกรรมเสริมหลักสูตร
                        @break
                    @case('innovation')
                        ด้านความคิดสร้างสรรค์และนวัตกรรม
                        @break
                    @case('good-conduct')
                        ด้านความประพฤติดี
                        @break
                @endswitch
            </p>
        </div>

        @isset($step2['approver'])
            <div>
                <p class="text-sm text-gray-500">ผู้รับรอง</p>
                <p class="text-gray-900">
                    {{ $step2['approver'] ?: '-' }}
                </p>
            </div>
        @endisset

        <p class="text-sm text-gray-500">ไฟล์แนบ</p>
        @if(session('award_registration.step2.documents'))
            <ul class="mt-2 space-y-1">
                @foreach(session('award_registration.step2.documents') as $doc)
                    <li class="flex items-center gap-2">
                        {{ basename($doc) }}
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-8">
        <p class="text-sm text-yellow-800 flex items-center gap-2">
            <span><svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M12 16.99V17M12 7V14M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#D48B00" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg></span>
            <span>
            เมื่อส่งใบสมัครแล้ว จะไม่สามารถแก้ไขข้อมูลได้
            กรุณาตรวจสอบความถูกต้องก่อนยืนยัน
        </span>
        </p>
    </div>

