@props(['application'])
<div class="flex flex-col gap-4 rounded-xl border border-gray-300 bg-white p-7 shadow-sm">
    <div class="flex gap-3 font-bold border-b pb-4">
        <x-icon name="trophy" class="text-blue-600" />
        <p>แบบเสนอนิสิตดีเด่น</p>
    </div>
    <div class="h-[600px] w-full bg-gray-100 rounded-lg overflow-hidden border">
        @if ($application->path)
            <iframe src="{{ route('file.preview', ['path' => $application->path]) }}" class="w-full h-full">
            </iframe>
        @else
            <div class="flex items-center justify-center h-full text-gray-400">ไม่พบไฟล์แบบเสนอ</div>
        @endif
    </div>
</div>
