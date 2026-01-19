<x-app-layout>
    <div class="mx-4 md:mx-12 my-4 p-4 border border-gray-400 rounded-lg">

    </div>
    <div class="my-4 flex gap-2 justify-center items-center">
        <div
            class="p-2 text-center rounded-lg border-gray-400 border cursor-pointer transition-all
            {{!request('role') ? 'bg-gray-600 text-white' : 'hover:bg-gray-600'}}"
            onclick="window.location.href='{{route('users.index')}}'"
        >
            ทั้งหมด
        </div>
        @foreach($roles as $r)
            <div
                class="p-2 text-center rounded-lg border-gray-400 border hover:bg-gray-600 cursor-pointer transition-all
                {{request('role') == $r->value ? 'bg-gray-600 text-white' : 'hover:bg-gray-600'}}"
                onclick="window.location.href='{{ route('users.index', ['role' => $r->value]) }}'"
            >
                {{$r->label($r)}}
            </div>
        @endforeach
    </div>
    @foreach($users as $u)
        <div class="p-3 flex-col rounded-xl border-zinc-400 hover:bg-gray-400 cursor-pointer transition-all">
            {{$u->firstName.' '.$u->lastName}}
        </div>
    @endforeach

    <button
        class="p-3 rounded bg-zinc-400 flex justify-center items-center hover:bg-gray-600 cursor-pointer transition-all"
        type="button"
        onclick="window.location.href='{{route('users.create')}}'"
    >
        สร้างผู้ใช้
    </button>

</x-app-layout>


