<x-app-layout>
    <div class="p-4 md:p-10">
        <a class="flex gap-2 mb-6 md:mb-10" href="{{ route('main') }}">
            <x-icon name="arrow-left"></x-icon>
            <p>กลับหน้าหลัก</p>
        </a>
        <div class="mx-0 md:mx-12 my-4 md:p-4">
            <form action="{{ route('users.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center">

                @if (request('role'))
                    <input type="hidden" name="role" value="{{ request('role') }}">
                @endif

                <div class="w-full relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="ค้นหาชื่อ, นามสกุล, อีเมล หรือ Username..."
                        class="w-full border border-gray-300 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-green-500" />
                </div>

                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-all w-full md:w-auto">
                    ค้นหา
                </button>

                @if (request('search') || request('role'))
                    <a href="{{ route('users.index') }}"
                        class="text-gray-500 hover:text-red-500 underline text-sm whitespace-nowrap">
                        ล้างค่าค้นหา
                    </a>
                @endif

            </form>
        </div>
        <div class="my-4 flex flex-col md:flex-row gap-4 md:gap-2 justify-center items-center w-full">
            <div class="w-full md:hidden">
                <select 
                    class="w-full border border-gray-300 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-green-500 bg-white"
                    onchange="window.location.href=this.value">
                    <option value="{{ route('users.index') }}" {{ !request('role') ? 'selected' : '' }}>ทั้งหมด</option>
                    @foreach ($roles as $r)
                        <option value="{{ route('users.index', ['role' => $r->value]) }}" {{ request('role') == $r->value ? 'selected' : '' }}>
                            {{ $r->label($r) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="hidden md:flex gap-2 justify-center items-center">
                <div class="p-2 text-center rounded-lg border-gray-400 border cursor-pointer transition-all
                    {{ !request('role') ? 'bg-gray-600 text-white' : 'hover:bg-gray-600' }}"
                    onclick="window.location.href='{{ route('users.index') }}'">
                    ทั้งหมด
                </div>
                @foreach ($roles as $r)
                    <div class="p-2 text-center rounded-lg border-gray-400 border hover:bg-gray-600 cursor-pointer transition-all
                        {{ request('role') == $r->value ? 'bg-gray-600 text-white' : 'hover:bg-gray-600' }}"
                        onclick="window.location.href='{{ route('users.index', ['role' => $r->value]) }}'">
                        {{ $r->label($r) }}
                    </div>
                @endforeach
            </div>

            <div class="w-full md:w-auto">
                <button
                    class="w-full md:w-auto py-2 px-4 rounded-lg bg-green-400 text-white font-bold flex justify-center items-center hover:bg-green-800 cursor-pointer transition-all"
                    type="button" onclick="window.location.href='{{ route('users.create') }}'">
                    สร้างผู้ใช้
                </button>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-0 md:p-6">
            @foreach ($users as $u)
                <x-user-card :user="$u" />
            @endforeach
        </div>
        <div class="mt-6 justify-center">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>
