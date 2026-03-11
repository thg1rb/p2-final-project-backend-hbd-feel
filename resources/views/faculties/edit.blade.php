<x-app-layout>
    <div class="p-10 flex flex-col gap-y-7">
        <a class="flex gap-2" href="{{ route('faculties.index') }}">
            <x-icon name="arrow-left"></x-icon>
            <p>กลับหน้าจัดการคณะ</p>
        </a>

        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
            <h1 class="font-bold text-2xl mb-6">แก้ไขข้อมูลคณะ</h1>

            <form action="{{ route('faculties.update', $faculty) }}" method="POST" class="flex flex-col gap-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700">ชื่อคณะ</label>
                    <input type="text" name="name" value="{{ $faculty->name }}" required
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                </div>
                <div class="flex justify-end mt-4">
                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded-md">อัปเดตข้อมูล</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
