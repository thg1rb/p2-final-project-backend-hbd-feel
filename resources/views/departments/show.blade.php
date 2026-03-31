<x-app-layout>
    <div class="p-10 flex flex-col gap-y-7">
        <a class="flex gap-2" href="{{ route('departments.index') }}">
            <x-icon name="arrow-left"></x-icon>
            <p>กลับหน้าจัดการภาควิชา</p>
        </a>

        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h1 class="font-bold text-2xl text-gray-800">รายละเอียดภาควิชา</h1>
                    <p class="text-gray-400">ข้อมูลพื้นฐานของภาควิชาในระบบ</p>
                </div>
                <a href="{{ route('departments.edit', $department) }}"
                    class="flex items-center gap-x-2 px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition">
                    <x-icon name="square-pen" size="18" />
                    <p class="hidden md:block">
                        แก้ไขข้อมูล
                    </p>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-1">
                    <p class="text-gray-400 text-sm">ชื่อภาควิชา</p>
                    <p class="text-lg font-semibold text-slate-700">{{ $department->name }}</p>
                </div>

                <div class="space-y-1">
                    <p class="text-gray-400 text-sm">คณะที่สังกัด</p>
                    <p class="text-lg font-semibold text-slate-700">{{ $department->faculty->name }}</p>
                </div>

                <div class="space-y-1">
                    <p class="text-gray-400 text-sm">วิทยาเขต</p>
                    <p class="text-lg font-semibold text-slate-700">
                        {{ \App\Enums\CampusType::label($department->faculty->campus) }}
                    </p>
                </div>

                <div class="space-y-1">
                    <p class="text-gray-400 text-sm">วันที่เพิ่มข้อมูล</p>
                    <p class="text-lg font-semibold text-slate-700">
                        {{ $department->created_at->addYears(543)->locale('th')->translatedFormat('j F Y H:i') }} น.
                    </p>
                </div>
            </div>

            <hr class="my-8">

            <div class="flex flex-row gap-x-4 justify-end">
                <form action="{{ route('departments.destroy', $department) }}" method="POST"
                    onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบภาควิชานี้? ข้อมูลผู้ใช้ที่เกี่ยวข้องอาจได้รับผลกระทบ')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="text-red-500 hover:text-red-700 font-medium items-center gap-x-1 border-2 rounded-xl p-3 border-red-500 flex gap-5 mt-5">
                        <x-icon name="trash-2" size="18" />
                        ลบภาควิชานี้
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
