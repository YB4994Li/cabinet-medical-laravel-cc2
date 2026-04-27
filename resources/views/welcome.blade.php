<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>YBMedicalClinic</title>
    @include('partials.favicon')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900 antialiased">
    <div class="min-h-screen flex flex-col">
        <header class="w-full px-6 py-5">
            <nav class="max-w-6xl mx-auto flex items-center justify-between">
                <a href="/" class="flex items-center gap-3">
                    <span class="w-12 h-12 rounded-xl bg-blue-700 flex items-center justify-center shadow-sm">
                        <img src="{{ asset('images/logo.png') }}" alt="YBMedicalClinic logo" class="w-9 h-9 object-contain">
                    </span>
                    <span>
                        <span class="block text-xl font-extrabold text-slate-950">YBMedicalClinic</span>
                        <span class="block text-xs uppercase tracking-widest text-slate-500">Patients & Appointments</span>
                    </span>
                </a>

                @if (Route::has('login'))
                    <div class="flex items-center gap-3">
                        @auth
                            <a href="{{ url('/dashboard') }}"
                               class="px-5 py-3 rounded-xl bg-blue-700 text-white font-bold hover:bg-blue-800">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                               class="px-5 py-3 rounded-xl text-slate-700 font-bold hover:bg-white hover:shadow-sm">
                                Log in
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                   class="px-5 py-3 rounded-xl border border-slate-300 bg-white font-bold text-slate-800 shadow-sm hover:border-blue-300 hover:text-blue-700">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </nav>
        </header>

        <main class="flex-1 flex items-center justify-center px-6 py-10">
            <section class="max-w-6xl w-full bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden grid grid-cols-1 lg:grid-cols-2">
                <div class="p-8 sm:p-12 lg:p-16 flex flex-col justify-center">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 text-blue-700 text-sm font-bold w-fit">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                        Clinic workspace ready
                    </div>

                    <h1 class="mt-8 text-4xl sm:text-5xl font-extrabold text-slate-950 leading-tight">
                        Manage appointments, doctors, and services in one place.
                    </h1>

                    <p class="mt-5 text-lg text-slate-600 leading-relaxed">
                        YBMedicalClinic gives patients, doctors, and admins a clean dashboard for scheduling, tracking status, and keeping clinical services organized.
                    </p>

                    <div class="mt-8 flex flex-col sm:flex-row gap-3">
                        @auth
                            <a href="{{ route('dashboard') }}"
                               class="text-center px-6 py-4 rounded-xl bg-blue-700 text-white font-extrabold hover:bg-blue-800">
                                Open Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                               class="text-center px-6 py-4 rounded-xl bg-blue-700 text-white font-extrabold hover:bg-blue-800">
                                Login to Portal
                            </a>
                            <a href="{{ route('register') }}"
                               class="text-center px-6 py-4 rounded-xl border border-slate-300 bg-white text-slate-800 font-extrabold hover:border-blue-300 hover:text-blue-700">
                                Create Account
                            </a>
                        @endauth
                    </div>

                    <div class="mt-10 grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-2xl font-extrabold text-slate-950">Role</p>
                            <p class="text-sm text-slate-500 mt-1">Admin, doctor, patient</p>
                        </div>
                        <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-2xl font-extrabold text-slate-950">Status</p>
                            <p class="text-sm text-slate-500 mt-1">Confirm or cancel</p>
                        </div>
                        <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-2xl font-extrabold text-slate-950">Alerts</p>
                            <p class="text-sm text-slate-500 mt-1">Notifications and email</p>
                        </div>
                    </div>
                </div>

                <div class="relative bg-blue-50 min-h-[520px] p-8 sm:p-12 flex items-center overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-100 via-blue-50 to-white"></div>
                    <div class="absolute -right-24 -top-24 w-80 h-80 rounded-full bg-blue-200/60 blur-3xl"></div>
                    <div class="absolute -left-28 -bottom-28 w-96 h-96 rounded-full bg-slate-200/70 blur-3xl"></div>

                    <div class="relative w-full space-y-5">
                        <div class="bg-white/80 border border-white rounded-2xl shadow-sm p-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Today</p>
                                    <h2 class="text-2xl font-extrabold mt-1">Appointment Queue</h2>
                                </div>
                                <span class="w-12 h-12 rounded-xl bg-blue-700 text-white flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M5 11h14M7 21h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z" />
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white/80 border border-white rounded-2xl shadow-sm p-5">
                                <p class="text-sm font-bold text-slate-500">Doctors</p>
                                <p class="text-4xl font-extrabold mt-2 text-slate-950">24</p>
                            </div>
                            <div class="bg-white/80 border border-white rounded-2xl shadow-sm p-5">
                                <p class="text-sm font-bold text-slate-500">Services</p>
                                <p class="text-4xl font-extrabold mt-2 text-slate-950">18</p>
                            </div>
                        </div>

                        <div class="bg-white/80 border border-white rounded-2xl shadow-sm p-5">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-bold text-slate-950">Cardiology consultation</p>
                                        <p class="text-sm text-slate-500">Dr. assigned, pending status</p>
                                    </div>
                                    <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">PENDING</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-bold text-slate-950">Dermatology follow-up</p>
                                        <p class="text-sm text-slate-500">Patient notified by email</p>
                                    </div>
                                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold">CONFIRMED</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
