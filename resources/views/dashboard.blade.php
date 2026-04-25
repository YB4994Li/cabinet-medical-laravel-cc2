@extends('layouts.main')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-extrabold text-slate-900">Practice Overview</h1>
    <p class="text-slate-500 mt-2">Operational performance for the current clinical cycle.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase">Total Appointments</p>
                <h2 class="text-5xl font-extrabold mt-4">{{ \App\Models\Appointment::count() }}</h2>
                <!-- <p class="text-sm text-green-600 font-semibold mt-4">↗ Active schedule</p> -->
            </div>
            <div class="w-12 h-12 bg-blue-100 text-blue-700 rounded-xl flex items-center justify-center text-xl">
                📅
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase">Total Services</p>
                <h2 class="text-5xl font-extrabold mt-4">{{ \App\Models\Service::count() }}</h2>
                <!-- <p class="text-sm text-slate-500 font-semibold mt-4">Stable activity</p> -->
            </div>
            <div class="w-12 h-12 bg-slate-100 text-slate-700 rounded-xl flex items-center justify-center text-xl">
                🧰
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase">Total Patients</p>
                <h2 class="text-5xl font-extrabold mt-4">{{ \App\Models\User::where('role', 'patient')->count() }}</h2>
                <!-- <p class="text-sm text-green-600 font-semibold mt-4">↗ Patient records</p> -->
            </div>
            <div class="w-12 h-12 bg-blue-100 text-blue-700 rounded-xl flex items-center justify-center text-xl">
                👥
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="p-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold">Recent Appointments</h2>
            <p class="text-slate-500 text-sm mt-1">A live feed of upcoming and completed clinical sessions.</p>
        </div>

        <a href="{{ route('appointments.index') }}" class="text-blue-700 font-bold text-sm">
            VIEW ALL →
        </a>
    </div>

    <table class="w-full">
        <thead class="bg-slate-100 text-slate-600 text-xs uppercase">
            <tr>
                <th class="text-left p-4">Patient</th>
                <th class="text-left p-4">Service</th>
                <th class="text-left p-4">Date & Time</th>
                <th class="text-left p-4">Status</th>
            </tr>
        </thead>

        <tbody>
            @foreach(\App\Models\Appointment::with(['patient','service'])->latest()->take(5)->get() as $appointment)
            <tr class="border-t border-slate-100">
                <td class="p-4 font-semibold">{{ $appointment->patient->name }}</td>
                <td class="p-4 text-slate-600">{{ $appointment->service->name }}</td>
                <td class="p-4 text-slate-600">
                    {{ $appointment->appointment_date }} <br>
                    <span class="text-sm text-slate-400">{{ $appointment->appointment_time }}</span>
                </td>
                <td class="p-4">
                    @if($appointment->status == 'confirmed')
                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold">CONFIRMED</span>
                    @elseif($appointment->status == 'cancelled')
                        <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold">CANCELLED</span>
                    @else
                        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">PENDING</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection