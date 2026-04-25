<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>YBMedicalClinic</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900">

<div class="min-h-screen flex">

    <aside class="w-64 bg-white border-r border-slate-200 flex flex-col">
        <div class="p-6">
            <h1 class="text-2xl font-bold text-blue-700">YBMedicalClinic</h1>
            <p class="text-xs text-slate-500 uppercase tracking-widest mt-1">
            Medical Management System
            </p>
        </div>

        <nav class="flex-1 px-4 space-y-2">
            <a href="{{ route('dashboard') }}"
               class="block px-4 py-3 rounded-xl font-semibold {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-600' : 'text-slate-600 hover:bg-slate-100' }}">
                Dashboard
            </a>

            <a href="{{ route('appointments.index') }}"
               class="block px-4 py-3 rounded-xl font-semibold {{ request()->routeIs('appointments.*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-600' : 'text-slate-600 hover:bg-slate-100' }}">
                Appointments
            </a>

            <a href="{{ route('services.index') }}"
               class="block px-4 py-3 rounded-xl font-semibold {{ request()->routeIs('services.*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-600' : 'text-slate-600 hover:bg-slate-100' }}">
                Services
            </a>
        </nav>

        <div class="p-4 border-t border-slate-200">
            <a href="{{ route('appointments.create') }}"
               class="block text-center bg-blue-700 text-white px-4 py-3 rounded-xl font-bold hover:bg-blue-800">
                + Add New Entry
            </a>

            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button class="w-full text-left px-4 py-3 rounded-xl font-bold text-red-600 hover:bg-red-50">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1">
        <header class="h-16 bg-white border-b border-slate-200 px-8 flex justify-between items-center">
            <div class="font-bold text-lg">Medical Appointment System</div>

            <div class="flex items-center gap-4">
                <span class="text-sm text-slate-500">Logged in as</span>
                <span class="font-semibold">{{ Auth::user()->name }}</span>
            </div>
        </header>

        <section class="p-8">
            @yield('content')
        </section>
    </main>

</div>

</body>
</html>