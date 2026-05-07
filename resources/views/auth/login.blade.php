<x-guest-layout>
    <div class="auth-shell px-6 py-7 text-white sm:px-8">
        <div class="mb-6 flex flex-col items-center text-center">
            <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-[22px] border border-white/20 bg-white/12 shadow-[inset_0_1px_0_rgba(255,255,255,0.18)] backdrop-blur-md">
                <x-application-logo class="h-10 w-10" />
            </div>
            <h1 class="text-2xl font-semibold">Login</h1>
        </div>

        <div class="mb-5 flex rounded-full border border-white/10 bg-slate-950/25 p-1">
            <a href="{{ route('login') }}" class="auth-tab auth-tab-active">Masuk</a>
            <a href="{{ route('register') }}" class="auth-tab auth-tab-idle">Daftar</a>
        </div>

        <x-auth-session-status class="mb-4 rounded-2xl border border-emerald-300/20 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-100" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="text-sm font-medium text-slate-100">Email</label>
                <x-text-input id="email" class="input-shell" type="email" name="email" :value="old('email')" placeholder="username@gmail.com" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-rose-300" />
            </div>

            <div>
                <div class="mb-2 flex items-center justify-between">
                    <label for="password" class="text-sm font-medium text-slate-100">Password</label>
                    @if (Route::has('password.request'))
                        <a class="text-xs font-medium text-cyan-100 transition hover:text-white" href="{{ route('password.request') }}">
                            Lupa password?
                        </a>
                    @endif
                </div>
                <x-text-input id="password" class="mt-0 input-shell" type="password" name="password" placeholder="Password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-rose-300" />
            </div>

            <label for="remember_me" class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/6 px-4 py-2.5 text-sm text-slate-200 backdrop-blur">
                <input id="remember_me" type="checkbox" class="rounded border-white/20 bg-transparent text-cyan-400 shadow-sm focus:ring-cyan-300" name="remember">
                <span>Ingat saya</span>
            </label>

            <button type="submit" class="glass-button">
                Sign in
            </button>

            <p class="pt-1 text-center text-sm text-slate-200/90">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-semibold text-cyan-100 hover:text-white">Daftar</a>
            </p>
        </form>
    </div>
</x-guest-layout>
