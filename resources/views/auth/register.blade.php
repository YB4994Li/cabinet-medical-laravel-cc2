<x-guest-layout>
    <div class="min-h-screen bg-slate-50 flex flex-col items-center justify-center p-6">
        <div class="text-center mb-8">
            <a href="/" class="inline-flex w-20 h-20 rounded-2xl bg-blue-700 shadow-sm items-center justify-center">
                <img src="{{ asset('images/logo.png') }}" alt="YBMedicalClinic logo" class="w-14 h-14 object-contain">
            </a>
            <h1 class="mt-5 text-4xl font-extrabold text-slate-950">YBMedicalClinic</h1>
            <p class="mt-2 text-slate-500">Healthcare appointments and patient management.</p>
        </div>

        <div class="w-full max-w-xl bg-white border border-slate-200 rounded-2xl shadow-xl p-8 sm:p-10">
            <div class="mb-8">
                <h2 class="text-3xl font-extrabold text-slate-950">Create Account</h2>
                <p class="mt-2 text-slate-500">Register to access your clinical workspace.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-bold text-slate-700 uppercase tracking-widest mb-2">Full Name</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM12 14c-4 0-7 2-7 4v1h14v-1c0-2-3-4-7-4z" />
                            </svg>
                        </span>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                               class="w-full border border-slate-300 rounded-xl py-4 pl-12 pr-4 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 uppercase tracking-widest mb-2">Email Address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l9 6 9-6M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2z" />
                            </svg>
                        </span>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                               class="w-full border border-slate-300 rounded-xl py-4 pl-12 pr-4 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <label for="password" class="block text-sm font-bold text-slate-700 uppercase tracking-widest mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2zm10-10V7a4 4 0 0 0-8 0v4" />
                            </svg>
                        </span>
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                               class="w-full border border-slate-300 rounded-xl py-4 pl-12 pr-4 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-bold text-slate-700 uppercase tracking-widest mb-2">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                           class="w-full border border-slate-300 rounded-xl py-4 px-4 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <button type="submit" class="w-full bg-blue-700 text-white py-4 rounded-xl font-extrabold text-lg hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                    Register
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-slate-200 text-center text-slate-600">
                Already have an account?
                <a href="{{ route('login') }}" class="font-bold text-blue-700 hover:text-blue-800">Login</a>
            </div>
        </div>

        <div class="mt-8 flex flex-wrap items-center justify-center gap-4 text-xs font-bold uppercase tracking-widest text-slate-600">
            <span class="px-4 py-2 rounded-full bg-white border border-slate-200">Systems Secure</span>
            <span class="px-4 py-2 rounded-full bg-white border border-slate-200">Clinic Ready</span>
        </div>
    </div>
</x-guest-layout>
