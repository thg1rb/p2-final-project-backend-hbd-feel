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
                    ด้านกิจกรรมนอกหลักสูตร
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

    @if(!empty($step2['activity_types']))
        <div>
            <p class="text-sm text-gray-500">ลักษณะกิจกรรม</p>
            <ul class="list-disc list-inside text-gray-900">
                @foreach ($step2['activity_types'] as $type)
                    <li>
                        @switch($type)
                            @case('community')
                                กิจกรรมเพื่อประโยชน์ต่อชุมชนหรือส่วนรวม
                                @break
                            @case('competition')
                                การแข่งขันทางวิชาการ / ศิลปวัฒนธรรม
                                @break
                            @case('leadership')
                                ดำรงตำแหน่งผู้นำนิสิต / กรรมการองค์กรนิสิต
                                @break
                        @endswitch
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <p class="text-sm text-gray-500">วันที่ได้รับรางวัล</p>
            <p class="text-gray-900">{{ $step2['award_date'] ?? '-' }}</p>
        </div>

        <div>
            <p class="text-sm text-gray-500">โครงการ / รายการที่เข้าร่วม</p>
            <p class="text-gray-900">{{ $step2['project_name'] ?? '-' }}</p>
        </div>

        <div>
            <p class="text-sm text-gray-500">ชื่อทีม</p>
            <p class="text-gray-900">{{ $step2['team_name'] ?? '-' }}</p>
        </div>

        <div>
            <p class="text-sm text-gray-500">ชื่อผลงาน</p>
            <p class="text-gray-900">{{ $step2['work_name'] ?? '-' }}</p>
        </div>

        <div>
            <p class="text-sm text-gray-500">รางวัลที่ได้รับ</p>
            <p class="text-gray-900">{{ $step2['award_name'] ?? '-' }}</p>
        </div>

        <div>
            <p class="text-sm text-gray-500">หน่วยงานผู้จัด</p>
            <p class="text-gray-900">{{ $step2['organizer'] ?? '-' }}</p>
        </div>
    </div>

    <div>
        <p class="text-sm text-gray-500">เอกสารแนบ</p>

        @if(!empty($step2['documents']))
            <ul class="mt-2 space-y-1 list-disc list-inside text-gray-900">
                @foreach ($step2['documents'] as $doc)
                    <li>{{ basename($doc) }}</li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-400">- ไม่มีไฟล์แนบ -</p>
        @endif
    </div>
</div>

<div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-8">
    <p class="text-sm text-yellow-800 flex items-center gap-2">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path d="M12 16.99V17M12 7V14M21 12C21 16.97 16.97 21 12 21C7.03 21 3 16.97 3 12C3 7.03 7.03 3 12 3C16.97 3 21 7.03 21 12Z"
                  stroke="#D48B00" stroke-width="1.5" stroke-linecap="round"/>
        </svg>
        <span>
            เมื่อส่งใบสมัครแล้ว จะไม่สามารถแก้ไขข้อมูลได้
            กรุณาตรวจสอบความถูกต้องก่อนยืนยัน
        </span>
    </p>
</div>
