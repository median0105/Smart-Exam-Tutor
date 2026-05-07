<x-guest-layout>
    <div class="auth-shell px-6 py-7 text-white sm:px-8">
        <div class="mb-6 flex flex-col items-center text-center">
            <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-[22px] border border-white/20 bg-white/12 shadow-[inset_0_1px_0_rgba(255,255,255,0.18)] backdrop-blur-md">
                <x-application-logo class="h-10 w-10" />
            </div>
            <h1 class="text-2xl font-semibold">Register</h1>
        </div>

        <div class="mb-5 flex rounded-full border border-white/10 bg-slate-950/25 p-1">
            <a href="{{ route('login') }}" class="auth-tab auth-tab-idle">Masuk</a>
            <a href="{{ route('register') }}" class="auth-tab auth-tab-active">Daftar</a>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="text-sm font-medium text-slate-100">Nama</label>
                <x-text-input id="name" class="input-shell" type="text" name="name" :value="old('name')" placeholder="Nama lengkap" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-rose-300" />
            </div>

            <div>
                <label for="email" class="text-sm font-medium text-slate-100">Email</label>
                <x-text-input id="email" class="input-shell" type="email" name="email" :value="old('email')" placeholder="username@gmail.com" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-rose-300" />
            </div>

            <div>
                <label for="password" class="text-sm font-medium text-slate-100">Password</label>
                <x-text-input id="password" class="input-shell" type="password" name="password" placeholder="Password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-rose-300" />
            </div>

            <div>
                <label for="password_confirmation" class="text-sm font-medium text-slate-100">Konfirmasi Password</label>
                <x-text-input id="password_confirmation" class="input-shell" type="password" name="password_confirmation" placeholder="Konfirmasi password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-rose-300" />
            </div>

            <button type="submit" class="glass-button">
                Register
            </button>

            <p class="pt-1 text-center text-sm text-slate-200/90">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-semibold text-cyan-100 hover:text-white">Masuk</a>
            </p>
        </form>
    </div>
</x-guest-layout>
