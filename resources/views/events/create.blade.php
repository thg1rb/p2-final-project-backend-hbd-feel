<x-app-layout>
    <div class="p-10 flex flex-col gap-y-7">
        <a class="flex gap-2" href="{{ route('events.index') }}">
            <x-icon name="arrow-left"></x-icon>
            <p>ย้อนกลับ</p>
        </a>
        <h1 class="font-bold text-[32px] text-center">เพิ่มรอบการให้รางวัล</h1>
        @php $viewMode = 'create'; @endphp
        @include('events.partials.event-information', ['event' => $event])
    </div>
</x-app-layout>
