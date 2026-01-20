<x-app-layout>
    <div class="p-10 flex flex-col gap-y-7">
        <a class="flex gap-2" href="{{ route('awards.index') }}">
            <x-icon name="arrow-left"></x-icon>
            <p>กลับหน้าจัดการหมวดรางวัล</p>
        </a>

        {{-- Header --}}
        <div class="flex flex-col justify-center items-center">
            <h1 class="font-bold text-[32px]">เพิ่มหมวดรางวัล</h1>
        </div>
        <form
            action="{{route('awards.store')}}"
            method="POST"
            class="flex items-center justify-center">
            @csrf
            <div class="flex flex-col justify-start items-start w-1/2 bg-white p-5 rounded-xl gap-5">
                <div class="w-full">
                    <label for="name" class="block text-sm font-medium text-gray-700">
                        ชื่อหมวดรางวัล  <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" placeholder="เช่น รางวัลเรียนดี..." value="{{ old('name') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm"
                    />
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="reward" class="block text-sm font-medium text-gray-700">
                        จำนวนเงินรางวัล  <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="reward" id="reward" placeholder="1000" value="{{ old('reward') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm"
                    />
                    @error('reward') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <button
                    class="py-2 px-5 rounded text-center text-white bg-[#226e64] hover:scale-105 cursor-pointer transition-all"
                    type="submit"
                >
                    เพิ่มหมวดรางวัล
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
