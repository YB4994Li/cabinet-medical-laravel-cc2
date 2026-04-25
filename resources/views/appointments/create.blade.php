@extends('layouts.main')

@section('content')

<div class="mb-8">
    <h1 class="text-4xl font-extrabold text-slate-900">Add Appointment</h1>
    <p class="text-slate-500 mt-2">Schedule a new medical appointment.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <form action="{{ route('appointments.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-2 gap-4 mb-5">

                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-2">Patient</label>
                    <select name="patient_id" class="w-full border border-slate-200 rounded-xl p-3">
                        @foreach($patients as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-2">Doctor</label>
                    <select name="doctor_id" class="w-full border border-slate-200 rounded-xl p-3">
                        @foreach($doctors as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="mb-5">
                <label class="block text-sm font-bold text-slate-600 mb-2">Service</label>
                <select name="service_id" class="w-full border border-slate-200 rounded-xl p-3">
                    @foreach($services as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-2">Date</label>
                    <input type="date" name="appointment_date"
                           class="w-full border border-slate-200 rounded-xl p-3">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-2">Time</label>
                    <input type="time" name="appointment_time"
                           class="w-full border border-slate-200 rounded-xl p-3">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-600 mb-2">Notes</label>
                <input type="text" name="notes"
                       class="w-full border border-slate-200 rounded-xl p-3">
            </div>

            <div class="flex justify-between">
                <a href="{{ route('appointments.index') }}" class="text-slate-500 font-semibold">
                    Cancel
                </a>

                <button class="bg-blue-700 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-800">
                    Create Appointment
                </button>
            </div>

        </form>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <h3 class="text-lg font-bold mb-4">Info</h3>

        <p class="text-slate-500 text-sm">
            Fill all required fields to schedule an appointment correctly.
        </p>

        <div class="mt-6 text-sm text-slate-500">
            <p>💡 Tip: Check availability before confirming.</p>
        </div>
    </div>

</div>

@endsection