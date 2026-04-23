<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg">

            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">
                KARIM JAYA COMPUTER
            </h2>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Validation Errors -->
            <x-input-error :messages="$errors->all()" class="mb-4" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" required autofocus
                        class="w-full mt-1 p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" required
                        class="w-full mt-1 p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Remember -->
                <div class="flex items-center justify-between mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="mr-2">
                        <span class="text-sm">Remember me</span>
                    </label>
                </div>

                <!-- Button -->
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                    Login
                </button>

                <!-- Register -->
                <p class="text-sm text-center mt-4">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-blue-600 hover:underline">
                        Daftar
                    </a>
                </p>
            </form>

        </div>
    </div>
</x-guest-layout>