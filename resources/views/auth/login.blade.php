<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-5">
            เข้าสู่ระบบ test

        </div>

        <div class="my-5 ">
            <a href="{{ route('google.redirect') }}"
                class="w-full flex justify-center items-center mt-4 px-4 py-2 border rounded-2xl hover:scale-105 transition-transform duration-300 ease-in-out">
                <img src="{{ asset('images/google.png') }}" class="mx-2 size-4">

                Continue with Google
            </a>
        </div>
        <!-- Email Address -->
        <div>
            <x-input-label for="credential" :value="__('ชื่อผู้ใช้ หรือ อีเมล')" />
            <x-text-input id="credential" class="block mt-1 w-full h-10 border p-3" type="credential" name="credential"
                :value="old('credential')" required autofocus autocomplete="credential" placeholder="bxxxxxxxxxx" />
            <x-input-error :messages="$errors->get('credential')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('รหัสผ่าน')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="current-password" placeholder="••••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('จดจำฉัน') }}</span>
            </label>
        </div>

        <div class="flex justify-center items-center my-4">
            <x-primary-button class="mx-3 w-full items-center justify-center bg-primary hover:scale-110 ">
                {{ __('เข้าสู่ระบบ') }}
            </x-primary-button>
        </div>

        <div class="flex items-center justify-end mt-4 gap-5">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('register') }}">
                สมัครสมาชิก
            </a>
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('password.request') }}">
                    ลืมรหัสผ่าน?
                </a>
            @endif

        </div>



        </div>
    </form>
</x-guest-layout>
