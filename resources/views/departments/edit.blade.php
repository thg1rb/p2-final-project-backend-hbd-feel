<x-app-layout>
    <div class="p-10 flex flex-col gap-y-7">
        <a class="flex gap-2" href="{{ route('departments.index') }}">
            <x-icon name="arrow-left"></x-icon>
            <p>กลับหน้าจัดการภาควิชา</p>
        </a>

        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
            <h1 class="font-bold text-2xl mb-6">แก้ไขข้อมูลภาควิชา</h1>

            <form action="{{ route('departments.update', $department) }}" method="POST" class="flex flex-col gap-y-4">
                @csrf
                @method('PUT')

                {{-- ชื่อภาควิชา --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">ชื่อภาควิชา</label>
                    <input type="text" name="name" value="{{ old('name', $department->name) }}" required
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- เลือกคณะ --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        สังกัดคณะ (วิทยาเขต: {{ \App\Enums\CampusType::label(auth()->user()->campus) }})
                    </label>
                    <select name="faculty_id" required
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                        @foreach ($faculties as $faculty)
                            <option value="{{ $faculty->id }}"
                                {{ $department->faculty_id == $faculty->id ? 'selected' : '' }}>
                                {{ $faculty->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('faculty_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-x-3 mt-6">
                    <a href="{{ route('departments.index') }}"
                        class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">ยกเลิก</a>
                    <button type="submit"
                        class="px-6 py-2 bg-primary text-white rounded-md transition-all hover:scale-95">
                        อัปเดตข้อมูล
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
