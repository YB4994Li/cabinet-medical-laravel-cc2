@extends('layouts.main')

@section('content')
<h1 class="text-3xl font-bold mb-2">Practice Overview</h1>
<p class="text-slate-500 mb-8">Operational performance for the current clinical cycle.</p>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-2xl border shadow-sm">
        <p class="text-sm font-bold text-slate-500">Total Appointments</p>
        <h2 class="text-4xl font-bold mt-3">{{ \App\Models\Appointment::count() }}</h2>
    </div>

    <div class="bg-white p-6 rounded-2xl border shadow-sm">
        <p class="text-sm font-bold text-slate-500">Total Services</p>
        <h2 class="text-4xl font-bold mt-3">{{ \App\Models\Service::count() }}</h2>
    </div>

    <div class="bg-white p-6 rounded-2xl border shadow-sm">
        <p class="text-sm font-bold text-slate-500">Total Users</p>
        <h2 class="text-4xl font-bold mt-3">{{ \App\Models\User::count() }}</h2>
    </div>
</div>
@endsection