<x-guest-layout>
    <div class="min-h-screen bg-slate-50 flex items-center justify-center p-6">
        <div class="w-full max-w-6xl bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden grid grid-cols-1 lg:grid-cols-2">
            <div class="relative min-h-[640px] bg-blue-50 p-10 lg:p-14 flex flex-col justify-center overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-100 via-blue-50 to-white"></div>
                <div class="absolute -right-20 -bottom-20 w-80 h-80 rounded-full bg-blue-200/40 blur-3xl"></div>
                <div class="absolute left-0 bottom-0 right-0 h-64 bg-gradient-to-t from-blue-200/60 to-transparent"></div>

                <div class="relative">
                    <div class="w-24 h-24 rounded-2xl bg-white shadow-sm border border-blue-100 flex items-center justify-center mb-8">
                        <img src="{{ asset('images/logo.png') }}" alt="YBMedicalClinic logo" class="w-16 h-16 object-contain">
                    </div>

                    <h1 class="text-4xl font-extrabold text-slate-950">YBMedicalClinic</h1>
                    <p class="mt-4 text-lg leading-relaxed text-blue-800 max-w-md">
                        Patients, appointments, doctors, and services managed from one clean clinical dashboard.
                    </p>

                    <div class="mt-10 space-y-4 max-w-md">
                        <div class="flex items-center gap-4 rounded-xl bg-white/70 border border-white p-4 shadow-sm">
                            <span class="w-10 h-10 rounded-lg bg-blue-700 text-white flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5-4v6c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-3 8 3z" />
                                </svg>
                            </span>
                            <span class="font-semibold text-slate-800">Secure role-based access</span>
                        </div>

                        <div class="flex items-center gap-4 rounded-xl bg-white/70 border border-white p-4 shadow-sm">
                            <span class="w-10 h-10 rounded-lg bg-blue-700 text-white flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M5 11h14M7 21h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z" />
                                </svg>
                            </span>
                            <span class="font-semibold text-slate-800">Appointment tracking and notifications</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-8 sm:p-12 lg:p-14 flex flex-col justify-center">
                <div class="mb-8">
                    <h2 class="text-3xl font-extrabold text-slate-950">Welcome Back</h2>
                    <p class="text-slate-500 mt-2">Access your medical clinic dashboard.</p>
                </div>

                <x-auth-session-status class="mb-5" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="email" class="text-sm font-bold text-slate-700 uppercase tracking-widest">Email Address</label>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l9 6 9-6M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2z" />
                                </svg>
                            </span>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                   class="w-full border border-slate-300 rounded-xl py-4 pl-12 pr-4 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="text-sm font-bold text-slate-700 uppercase tracking-widest">Password</label>
                            @if (Route::has('password.request'))
                                <a class="text-sm font-bold text-blue-700 hover:text-blue-800" href="{{ route('password.request') }}">
                                    Forgot Password?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2zm10-10V7a4 4 0 0 0-8 0v4" />
                                </svg>
                            </span>
                            <input id="password" type="password" name="password" required autocomplete="current-password"
                                   class="w-full border border-slate-300 rounded-xl py-4 pl-12 pr-4 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <label for="remember_me" class="flex items-center gap-3 text-slate-600">
                        <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-blue-700 shadow-sm focus:ring-blue-500" name="remember">
                        <span>Remember this workstation</span>
                    </label>

                    <button type="submit" class="w-full bg-blue-700 text-white py-4 rounded-xl font-extrabold text-lg hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                        Login to Portal
                    </button>
                </form>

                <div class="mt-8 pt-6 border-t border-slate-200 text-center text-slate-600">
                    Need an account?
                    <a href="{{ route('register') }}" class="font-bold text-blue-700 hover:text-blue-800">Create one</a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
