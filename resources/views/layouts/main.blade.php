<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>YBMedicalClinic</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900">

<div class="min-h-screen">

    <!-- Top Navbar -->
    <header class="h-20 bg-white border-b border-slate-200 px-6 flex items-center justify-between">
        <div class="flex items-center gap-3 w-72">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-16 h-16 object-contain drop-shadow-sm">
        </div>

        <div class="flex items-center gap-6">
            <button class="relative w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center">
                🔔
                <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>

            <div class="text-right">
                <p class="font-bold">{{ Auth::user()->name }}</p>
                <p class="text-xs text-slate-500 uppercase tracking-widest">
                    {{ Auth::user()->role }}
                </p>
            </div>

            <div class="w-11 h-11 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
        </div>
    </header>

    <div class="flex min-h-[calc(100vh-4rem)]">

        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-slate-200 flex flex-col">
            <div class="p-6">
                <p class="text-blue-700 font-extrabold uppercase tracking-widest">YBMedicalClinic</p>
                <p class="text-xs text-slate-500 uppercase mt-1">Patients & Appointments</p>
            </div>

            <nav class="flex-1 px-4 space-y-2">
                <a href="{{ route('dashboard') }}"
                   class="block px-4 py-3 rounded-xl font-bold {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-600' : 'text-slate-600 hover:bg-slate-100' }}">
                    Dashboard
                </a>

                <a href="{{ route('appointments.index') }}"
                   class="block px-4 py-3 rounded-xl font-bold {{ request()->routeIs('appointments.*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-600' : 'text-slate-600 hover:bg-slate-100' }}">
                    Appointments
                </a>

                <a href="{{ route('services.index') }}"
                   class="block px-4 py-3 rounded-xl font-bold {{ request()->routeIs('services.*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-600' : 'text-slate-600 hover:bg-slate-100' }}">
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

        <!-- Page Content -->
        <main class="flex-1 p-8 overflow-x-auto">
            @yield('content')
        </main>

    </div>
</div>

</body>
</html>