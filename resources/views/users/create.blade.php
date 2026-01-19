<x-app-layout>
    <form
        action="{{route('users.store')}}"
        method="POST"
        class="flex items-center justify-center"
    >
        @csrf
        <div class="flex-col gap-4 justify-center items-center">
            <div class="flex gap-3">
               <input type="text" name="firstName" id="first-name" placeholder="ชื่อจริง" required/>
                <input type="text" name="lastName" id="last-name" placeholder="นามสกุล" required/>
            </div>
            <div>
                <input type="email" name="email" id="email" placeholder="อีเมล" required/>
            </div>
            <div>
                <input type="password" name="password" id="password" placeholder="รหัสผ่าน" required/>
            </div>
            <div>

            <select name="role" id="role">
                @foreach($roles as $r)
                    <option value="{{$r->value}}">{{$r::label($r)}}</option>
                @endforeach
            </select>
            </div>
            <button
                class="py-2 px-5 rounded text-center bg-green-500 hover:bg-green-600 cursor-pointer transition-all"
                type="submit"
            >
                ยืนยัน
            </button>
        </div>
    </form>

</x-app-layout>
