@props(['application'])
<div class="flex flex-col gap-6 rounded-xl border border-gray-300 bg-white p-7 shadow-sm">
    <div class="flex gap-3 font-bold border-b pb-4">
        <x-icon name="book" class="text-blue-600" />
        <p>หลักฐานแนบใบคำร้อง</p>
    </div>

    <div class="flex flex-col gap-10">
        @foreach ($application->award->requirements as $index => $req)
            @php
                $doc = $application->documents[$req['id']] ?? null;
                $filePath = $doc['file_path'] ?? null;
            @endphp

            <div class="flex flex-col gap-3 text-sm">
                <p class="font-medium underline">{{ $index + 1 }}. {{ $req['name'] }}</p>

                @if ($filePath)
                    <div class="h-[500px] w-full border rounded-lg overflow-hidden bg-gray-50">
                        <iframe src="{{ route('file.preview', ['path' => $filePath]) }}" class="w-full h-full"></iframe>
                    </div>
                    <div class="flex justify-end mt-1">
                        <a href="{{ route('file.preview', ['path' => $filePath]) }}" target="_blank"
                            class="text-xs text-blue-500 hover:underline">
                            [ ขยายดูขนาดใหญ่ ]
                        </a>
                    </div>
                @else
                    <div class="p-10 border-2 border-dashed rounded-lg text-center text-gray-400">
                        ไม่พบไฟล์แนบ ({{ $req['id'] }})
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
