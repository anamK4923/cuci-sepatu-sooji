<x-guest-layout title="Sneat - Login">
    <div class="text-center mb-6">
        <img src="{{ asset('images/web/icon-512.png') }}" class="w-16 h-16 mx-auto mb-2" alt="Logo">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Welcome to Sneat! 👋</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Please sign-in to your account and start the adventure</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div>
            <input id="email" name="email" type="text" :value="old('email')" required autofocus autocomplete="username"
                placeholder="Email or Username"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2 mt-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4 relative">
            <input id="password" name="password" type="password" required autocomplete="current-password"
                placeholder="Password"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" name="remember"
                    class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Remember me</span>
            </label>

            @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}"
                class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Forgot Password?</a>
            @endif
        </div>

        <!-- Login Button -->
        <div class="mt-6">
            <button type="submit"
                class="w-full bg-[#7c69ef] hover:bg-[#7c69ef]/80 text-white font-semibold py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#7c69ef]">
                Login
            </button>
        </div>
    </form>

    <!-- Register -->
    <div class="text-center mt-6 text-sm text-gray-600 dark:text-gray-400">
        New on our platform?
        <a href="{{ route('register') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">Create an account</a>
    </div>

    <!-- OR separator -->
    <!-- <div class="flex items-center my-4">
        <div class="flex-grow border-t border-gray-300"></div>
        <span class="mx-3 text-sm text-gray-600 dark:text-gray-400">or</span>
        <div class="flex-grow border-t border-gray-300"></div>
    </div> -->

    <!-- Social Buttons -->
    <!-- <div class="flex justify-center gap-4">
        <a href="#" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white text-xl">
            <i class="fab fa-facebook-f"></i>
        </a>
        <a href="#" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white text-xl">
            <i class="fab fa-twitter"></i>
        </a>
        <a href="#" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white text-xl">
            <i class="fab fa-github"></i>
        </a>
        <a href="#" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white text-xl">
            <i class="fab fa-google"></i>
        </a>
    </div> -->
</x-guest-layout>