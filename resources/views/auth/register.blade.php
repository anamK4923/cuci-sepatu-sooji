<x-guest-layout>
    <div class="text-center mb-6">
        <img src="{{ asset('images/web/icon-512.png') }}" class="w-16 h-16 mx-auto mb-2" alt="Logo">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Buat Akun Soooji! 👋</h1>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Nama Lengkap -->
        <div>
            <x-input-label for="name" :value="'Nama Lengkap'" />
            <x-text-input id="name" class="mt-2 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2 focus:ring-2 focus:ring-[#006bcb] focus:outline-none" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Alamat Email -->
        <div class="mt-4">
            <x-input-label for="email" :value="'Email'" />
            <x-text-input id="email" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2 focus:ring-2 focus:ring-[#006bcb] focus:outline-none" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Kata Sandi -->
        <div class="mt-4">
            <x-input-label for="password" :value="'Kata Sandi'" />
            <x-text-input id="password" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2 focus:ring-2 focus:ring-[#006bcb] focus:outline-none"
                type="password"
                name="password"
                required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Konfirmasi Kata Sandi -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="'Konfirmasi Kata Sandi'" />
            <x-text-input id="password_confirmation" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2 focus:ring-2 focus:ring-[#006bcb] focus:outline-none"
                type="password"
                name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Role (hidden) -->
        <input type="hidden" name="role" value="member">

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#006bcb] dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                Sudah punya akun?
            </a>

            <x-primary-button class="ms-4 bg-[#006bcb] hover:bg-[#006bcb]/80 text-white font-semibold py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#006bcb]">
                Daftar
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>