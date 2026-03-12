<x-app-layout>
    <div class="p-10 flex flex-col gap-y-7">
        <a class="flex gap-2" href="{{ route('faculties.index') }}">
            <x-icon name="arrow-left"></x-icon>
            <p>กลับหน้าจัดการคณะ</p>
        </a>

        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
            <h1 class="font-bold text-2xl mb-6">รายละเอียดคณะ</h1>

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <p class="text-gray-400 text-sm">ชื่อคณะ</p>
                    <p class="text-lg font-semibold">{{ $faculty->name }}</p>
                </div>
                <hr>
                <div>
                    <p class="text-gray-400 text-sm">วิทยาเขต</p>
                    <p class="text-lg font-semibold">
                        {{ \App\Enums\CampusType::label($faculty->campus) }}
                    </p>
                </div>
            </div>

            <div class="mt-10 flex gap-2">
                <a href="{{ route('faculties.edit', $faculty) }}"
                    class="px-6 py-2 bg-yellow-500 text-white rounded-md">แก้ไข</a>
            </div>

            <div class="flex flex-row gap-x-4 justify-end">
                <form action="{{ route('faculties.destroy', $faculty) }}" method="POST"
                    onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบคณะนี้? ข้อมูลผู้ใช้ที่เกี่ยวข้องอาจได้รับผลกระทบ')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="text-red-500 hover:text-red-700 font-medium items-center gap-x-1 border-2 rounded-xl p-3 border-red-500 flex gap-5 mt-5">
                        <x-icon name="trash-2" size="18" />
                        ลบคณะนี้
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
