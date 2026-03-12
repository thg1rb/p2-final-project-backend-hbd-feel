<x-app-layout>
    <div class="p-10 flex flex-col gap-y-7">
        <a class="flex gap-2" href="{{ route('events.index') }}">
            <x-icon name="arrow-left"></x-icon>
            <p>ย้อนกลับ</p>
        </a>
        <h1 class="font-bold text-[32px] text-center">รายละเอียดรอบการให้รางวัล</h1>
        @php $viewMode = 'show'; @endphp
        @include('events.partials.event-information', ['event' => $event])
        <div class="p-10 flex flex-col gap-y-12 bg-white shadow-sm rounded-lg">
            <div>
                <h1 class="font-semibold text-[24px]">ลบรอบการให้รางวัล</h1>
                <p class="font-light text-[14px]">หากคุณลบข้อมูลดังกล่าว จะไม่สามารถนำข้อมูลกลับมาได้อีกต่อไปนี้</p>
            </div>
            <form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบรอบการให้รางวัลนี้?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="flex flex-row gap-x-2 items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                    <x-icon name="trash-2" />
                    ยืนยัน
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
