@extends('layouts.main')

@section('content')

@if(Auth::user()->role === 'admin')

<div class="mb-8">
    <h1 class="text-4xl font-extrabold text-slate-900">Practice Overview</h1>
    <p class="text-slate-500 mt-2">Operational performance for the clinic.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl border shadow-sm">
        <p class="text-xs font-bold text-slate-500 uppercase">Total Appointments</p>
        <h2 class="text-4xl font-extrabold mt-3">{{ \App\Models\Appointment::count() }}</h2>
    </div>

    <div class="bg-white p-6 rounded-2xl border shadow-sm">
        <p class="text-xs font-bold text-slate-500 uppercase">Total Services</p>
        <h2 class="text-4xl font-extrabold mt-3">{{ \App\Models\Service::count() }}</h2>
    </div>

    <div class="bg-white p-6 rounded-2xl border shadow-sm">
        <p class="text-xs font-bold text-slate-500 uppercase">Total Patients</p>
        <h2 class="text-4xl font-extrabold mt-3">
            {{ \App\Models\User::where('role','patient')->count() }}
        </h2>
    </div>
</div>

<div class="bg-white rounded-2xl border shadow-sm overflow-hidden">
    <div class="p-6">
        <h2 class="text-2xl font-bold">Recent Appointments</h2>
    </div>

    <table class="w-full">
        <thead class="bg-slate-100 text-xs uppercase text-slate-500">
            <tr>
                <th class="p-4 text-left">Patient</th>
                <th class="p-4 text-left">Doctor</th>
                <th class="p-4 text-left">Service</th>
                <th class="p-4 text-left">Date</th>
                <th class="p-4 text-left">Status</th>
            </tr>
        </thead>

        <tbody>
            @foreach(\App\Models\Appointment::with(['patient','doctor','service'])->latest()->take(5)->get() as $a)
            <tr class="border-t">
                <td class="p-4 font-bold">{{ $a->patient->name }}</td>
                <td class="p-4">{{ $a->doctor->name }}</td>
                <td class="p-4">{{ $a->service->name }}</td>
                <td class="p-4">{{ $a->appointment_date }}</td>
                <td class="p-4">{{ strtoupper($a->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@else

@php
    $user = Auth::user();

    if ($user->role === 'doctor') {
        $appointments = \App\Models\Appointment::with(['patient','service'])
            ->where('doctor_id', $user->id)
            ->latest()
            ->get();

        $title = 'Doctor Dashboard';
        $subtitle = 'Appointments assigned to you.';
        $firstColumn = 'Patient';
    } else {
        $appointments = \App\Models\Appointment::with(['doctor','service'])
            ->where('patient_id', $user->id)
            ->latest()
            ->get();

        $title = 'My Dashboard';
        $subtitle = 'Your personal appointments overview.';
        $firstColumn = 'Doctor';
    }
@endphp

<div class="mb-8">
    <h1 class="text-4xl font-extrabold text-slate-900">{{ $title }}</h1>
    <p class="text-slate-500 mt-2">{{ $subtitle }}</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl border shadow-sm">
        <p class="text-xs font-bold text-slate-500 uppercase">Appointments</p>
        <h2 class="text-4xl font-extrabold mt-3">{{ $appointments->count() }}</h2>
    </div>

    <div class="bg-white p-6 rounded-2xl border shadow-sm">
        <p class="text-xs font-bold text-slate-500 uppercase">Confirmed</p>
        <h2 class="text-4xl font-extrabold mt-3">{{ $appointments->where('status','confirmed')->count() }}</h2>
    </div>

    <div class="bg-white p-6 rounded-2xl border shadow-sm">
        <p class="text-xs font-bold text-slate-500 uppercase">Pending</p>
        <h2 class="text-4xl font-extrabold mt-3">{{ $appointments->where('status','pending')->count() }}</h2>
    </div>

    <div class="bg-white p-6 rounded-2xl border shadow-sm">
        <p class="text-xs font-bold text-slate-500 uppercase">Cancelled</p>
        <h2 class="text-4xl font-extrabold mt-3">{{ $appointments->where('status','cancelled')->count() }}</h2>
    </div>
</div>

<div class="bg-white rounded-2xl border shadow-sm overflow-hidden">
    <div class="p-6">
        <h2 class="text-2xl font-bold">
            {{ $user->role === 'doctor' ? 'Recent Assigned Appointments' : 'My Recent Appointments' }}
        </h2>
    </div>

    <table class="w-full">
        <thead class="bg-slate-100 text-xs uppercase text-slate-500">
            <tr>
                <th class="p-4 text-left">{{ $firstColumn }}</th>
                <th class="p-4 text-left">Service</th>
                <th class="p-4 text-left">Date</th>
                <th class="p-4 text-left">Time</th>
                <th class="p-4 text-left">Status</th>
            </tr>
        </thead>

        <tbody>
            @foreach($appointments->take(5) as $a)
            <tr class="border-t">
                <td class="p-4 font-bold">
                    {{ $user->role === 'doctor' ? $a->patient->name : $a->doctor->name }}
                </td>
                <td class="p-4">{{ $a->service->name }}</td>
                <td class="p-4">{{ $a->appointment_date }}</td>
                <td class="p-4">{{ $a->appointment_time }}</td>
                <td class="p-4">
                    @if($a->status == 'confirmed')
                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold">CONFIRMED</span>
                    @elseif($a->status == 'cancelled')
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

@endif

@endsection