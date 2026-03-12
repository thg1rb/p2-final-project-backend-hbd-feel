<x-guest-layout>
    <form method="POST" action="{{ route('force-change-password') }}">
        @csrf

        <div class="text-xl my-4 flex justify-center items-center">
            โปรดเปลี่ยนรหัสผ่านก่อนเข้าใช้งานระบบ
        </div>
        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('รหัสผ่านใหม่')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('ยืนยันรหัสผ่านใหม่')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('แก้ไขรหัสผ่าน') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
