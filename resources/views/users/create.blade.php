<x-app-layout>
    <form
        action="{{route('users.store')}}"
        method="POST"
        class="flex items-center justify-center"
    >
        @csrf
        <div class="flex flex-col gap-5">
            <div class="mt-5 mb-3 text-3xl text-center">สร้างผู้ใช้ </div>
            <div class="flex gap-3">
                <div>
                    <label for="firstName" class="block text-sm font-medium text-gray-700">
                        ชื่อจริง
                    </label>
                    @error('firstName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    <input type="text" name="firstName" id="first-name" placeholder="ชื่อจริง"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm"
                    />
                </div>
                <div>
                    <label for="lastName" class="block text-sm font-medium text-gray-700">
                        นามสกุล
                    </label>
                    @error('lastName') <span class="inline- text-red-500 text-xs">{{ $message }}</span> @enderror
                    <input type="text" name="lastName" id="last-name" placeholder="นามสกุล"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm"
                    />
                </div>
            </div>
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">ชื่อผู้ใช้</label>
                @error('username') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                <input type="text" name="username" id="username" placeholder="ชื่อผู้ใช้"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm"
                />
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">
                    อีเมล
                </label>
                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                <input type="email" name="email" id="email" placeholder="อีเมล"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm"
                />
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">รหัสผ่าน</label>
                @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                <input type="password" name="password" id="password" placeholder="รหัสผ่าน"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm"
                />
            </div>
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700">ตำแหน่ง</label>
                <select name="role" id="role"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm"
                >
                    @foreach($roles as $r)
                        @if($r->value != 'ADMIN') @endif
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
