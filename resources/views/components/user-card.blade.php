@props(['user'])

<div
    class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 flex flex-col md:flex-row items-start md:items-center gap-4 hover:border-green-400 transition-colors cursor-pointer"
    onclick="window.location.href='{{route('users.show', ['user' => $user])}}'"
>

    <div class="h-12 w-12 rounded-full bg-green-100 text-green-700 flex items-center justify-center font-bold text-lg shrink-0">
        {{ substr($user->firstName, 0, 1) }}
    </div>

    <div class="flex-1 min-w-0">
        <div class="flex items-center gap-2 mb-1">
            <h3 class="text-base font-semibold text-gray-900 truncate">
                {{ $user->firstName }} {{ $user->lastName }}
            </h3>

            <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">
                {{ $user->role->label($user->role) }}
            </span>
        </div>

        <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
        <p class="text-xs text-gray-400 mt-1">Username: {{ $user->username }}</p>
    </div>

    <div class="flex items-center gap-3 w-full md:w-auto mt-2 md:mt-0 border-t md:border-t-0 pt-3 md:pt-0">
        <a href="{{ route('users.edit', $user) }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
            แก้ไข
        </a>
        <span class="text-gray-300 hidden md:inline">|</span>
        <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium" onclick="return confirm('คุณแน่ใจที่จะลบผู้ใช้หรือไม่')">
                ลบ
            </button>
        </form>
    </div>
</div>
