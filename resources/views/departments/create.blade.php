<x-app-layout>
    <div class="p-10">
        <div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-md">
            <h1 class="text-2xl font-bold mb-6">เพิ่มภาควิชาใหม่</h1>
            <form action="{{ route('departments.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium">ชื่อภาควิชา</label>
                    <input type="text" name="name" required class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium">สังกัดคณะ (วิทยาเขต:
                        {{ \App\Enums\CampusType::label(auth()->user()->campus) }})</label>
                    <select name="faculty_id" required class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                        <option value="">-- เลือกคณะ --</option>
                        @foreach ($faculties as $faculty)
                            <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-2 pt-4">
                    <a href="{{ route('departments.index') }}" class="px-4 py-2 border rounded-md">ยกเลิก</a>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
