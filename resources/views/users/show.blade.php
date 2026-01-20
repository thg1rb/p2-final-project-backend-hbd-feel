<x-app-layout>
    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

        <div class="mb-6">
            <a href="{{ route('users.index') }}" class="flex items-center text-gray-500 hover:text-gray-700 transition">
                กลับหน้ารายชื่อ
            </a>
        </div>

        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
            <div class="h-32 bg-gradient-to-r from-gray-700 to-gray-900 relative"></div>
            <div class="px-6 pb-8 relative mt-3">

                <div class="relative -mt-16 mb-6 flex justify-between items-end">
                    <div class="flex items-end gap-6">
                        <div class="h-32 w-32 rounded-full border-4 border-white bg-white shadow-md flex items-center justify-center text-4xl font-bold text-gray-700 overflow-hidden">
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                {{ substr($user->firstName, 0, 1) }}
                            </div>
                        </div>

                        <div class="mb-2 hidden sm:block">
                            <h1 class="text-3xl font-bold text-gray-900">{{ $user->firstName }} {{ $user->lastName }}</h1>
                        </div>
                    </div>

                    <div class="mb-4">
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold border bg-gray-100 text-gray-800 border-gray-200 shadow-sm">
                        {{ $user->role->label($user->role) ?? $user->role->value }}
                    </span>
                    </div>
                </div>

                <div class="sm:hidden mb-6 text-center">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $user->firstName }} {{ $user->lastName }}</h1>
                    <p class="text-gray-500">@ {{ $user->username }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-gray-100 pt-8">

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center gap-2">
                            ข้อมูลบัญชี
                        </h3>
                        <div class="space-y-4">
                            <div class="bg-gray-50 px-4 py-3 rounded-lg">
                                <div class="text-sm font-medium text-gray-500">อีเมล</div>
                                <div class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-mono">{{ $user->email }}</div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 rounded-lg">
                                <div class="text-sm font-medium text-gray-500">ชื่อผู้ใช้</div>
                                <div class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->username }}</div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center gap-2">
                            ข้อมูลระบบ
                        </h3>
                        <div class="space-y-4">
                            <div class="bg-gray-50 px-4 py-3 rounded-lg flex justify-between">
                                <div>
                                    <div class="text-sm font-medium text-gray-500">วันที่สร้างบัญชี</div>
                                    <div class="text-sm text-gray-900">{{ $user->created_at->format('d M Y, H:i') }}</div>
                                </div>
                                <span class="text-xs text-gray-400 self-center">
                                {{ $user->created_at->diffForHumans() }}
                            </span>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 rounded-lg flex justify-between">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">แก้ไขล่าสุด</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->updated_at->format('d M Y, H:i') }}</dd>
                                </div>
                                <span class="text-xs text-gray-400 self-center">
                                {{ $user->updated_at->diffForHumans() }}
                            </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t border-gray-100">
                <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                    แก้ไขข้อมูล
                </a>

                <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบผู้ใช้นี้?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                        ลบบัญชี
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
