<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('โปรดกรอก email ที่ท่านใช้สมัครระบบ เพื่อรับลิงก์ reset รหัสผ่าน') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" placeholder="example.ex@ku.th" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-center mt-4 ">
            <x-primary-button class="bg-[#2A6D46] hover:scale-110 hover:bg-[#2A6D46]">
                {{ __('รับลิงก์ reset รหัสผ่าน') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
