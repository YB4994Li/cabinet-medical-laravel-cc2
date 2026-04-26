@extends('layouts.main')

@section('content')
<div class="flex justify-between items-start mb-8">
    <div>
        <h1 class="text-4xl font-extrabold text-slate-900">Appointments</h1>
        <p class="text-slate-500 mt-2">Manage patient bookings, doctors, services and appointment status.</p>
    </div>

    <a href="{{ route('appointments.create') }}"
       class="bg-blue-700 text-white px-5 py-3 rounded-xl font-bold shadow-sm hover:bg-blue-800">
        + Add Appointment
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <p class="text-xs font-bold text-slate-500 uppercase">Total Appointments</p>
        <h2 class="text-4xl font-extrabold mt-3">{{ $appointments->total() }}</h2>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <p class="text-xs font-bold text-slate-500 uppercase">Confirmed</p>
        <h2 class="text-4xl font-extrabold mt-3">{{ $appointments->where('status', 'confirmed')->count() }}</h2>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <p class="text-xs font-bold text-slate-500 uppercase">Pending</p>
        <h2 class="text-4xl font-extrabold mt-3">{{ $appointments->where('status', 'pending')->count() }}</h2>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <p class="text-xs font-bold text-slate-500 uppercase">Cancelled</p>
        <h2 class="text-4xl font-extrabold mt-3">{{ $appointments->where('status', 'cancelled')->count() }}</h2>
    </div>

</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="p-6 flex justify-between items-center border-b border-slate-200">
        <div>
            <h2 class="text-2xl font-bold">Appointment Schedule</h2>
            <p class="text-slate-500 text-sm mt-1">Search and manage all medical appointments.</p>
        </div>

        <input type="text" id="search" placeholder="Search by patient or doctor..."
               class="w-72 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <table class="w-full">
        <thead class="bg-slate-100 text-slate-600 text-xs uppercase">
            <tr>
                <th class="text-left p-4">Patient</th>
                <th class="text-left p-4">Doctor</th>
                <th class="text-left p-4">Service</th>
                <th class="text-left p-4">Date</th>
                <th class="text-left p-4">Time</th>
                <th class="text-left p-4">Status</th>
                <th class="text-left p-4">Actions</th>
            </tr>
        </thead>

        <tbody id="appointmentsTable">
            @foreach($appointments as $a)
            <tr class="border-t border-slate-100 hover:bg-slate-50">
                <td class="p-4 font-bold text-slate-900">{{ $a->patient->name }}</td>
                <td class="p-4 text-slate-600">{{ $a->doctor->name }}</td>
                <td class="p-4 text-slate-600">{{ $a->service->name }}</td>
                <td class="p-4 text-slate-600">{{ $a->appointment_date }}</td>
                <td class="p-4 text-slate-600">{{ $a->appointment_time }}</td>
                <td class="p-4">
                    @if($a->status == 'confirmed')
                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold">CONFIRMED</span>
                    @elseif($a->status == 'cancelled')
                        <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold">CANCELLED</span>
                    @else
                        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">PENDING</span>
                    @endif
                </td>
                <td class="p-4">
                    <div class="flex gap-2">
                        <a href="{{ route('appointments.edit', $a->id) }}"
                           class="px-3 py-1 rounded-lg bg-yellow-100 text-yellow-700 font-bold text-sm">
                            Edit
                        </a>
                        <button onclick="openModal({{ $a->id }})"
                                class="px-3 py-1 rounded-lg bg-red-100 text-red-700 font-bold text-sm">
                            Delete
                        </button>
                        @if(Auth::user()->role === 'doctor' || Auth::user()->role === 'admin')
                            <form action="{{ route('appointments.updateStatus', $a->id) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                @method('PUT')

                                <select name="status"
                                        class="border border-slate-200 rounded-lg px-3 py-1 pr-8 bg-white text-sm font-bold focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="pending" {{ $a->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ $a->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="cancelled" {{ $a->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>

                                <button type="submit"
                                        class="px-3 py-1 rounded-lg bg-green-100 text-green-700 font-bold text-sm">
                                    Update
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="p-6">
    {{ $appointments->links() }}
    </div>
</div>

<div id="deleteModal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-6 w-96 shadow-xl">
        <h2 class="text-xl font-bold mb-2">Delete Appointment</h2>
        <p class="text-slate-500 mb-6">Are you sure you want to delete this appointment?</p>

        <form id="deleteForm" method="POST" class="flex gap-3">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-xl font-bold">Yes Delete</button>
            <button type="button" onclick="closeModal()" class="bg-slate-100 px-4 py-2 rounded-xl font-bold">Cancel</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
document.getElementById('search').addEventListener('keyup', function() {
    let query = this.value;

    axios.get('/appointments/search?q=' + query)
        .then(response => {
            let rows = '';

            response.data.forEach(a => {
                let badge = `<span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">PENDING</span>`;

                if (a.status === 'confirmed') {
                    badge = `<span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold">CONFIRMED</span>`;
                }

                if (a.status === 'cancelled') {
                    badge = `<span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold">CANCELLED</span>`;
                }

                rows += `
                <tr class="border-t border-slate-100 hover:bg-slate-50">
                    <td class="p-4 font-bold text-slate-900">${a.patient.name}</td>
                    <td class="p-4 text-slate-600">${a.doctor.name}</td>
                    <td class="p-4 text-slate-600">${a.service.name}</td>
                    <td class="p-4 text-slate-600">${a.appointment_date}</td>
                    <td class="p-4 text-slate-600">${a.appointment_time}</td>
                    <td class="p-4">${badge}</td>
                    <td class="p-4">
                        <div class="flex gap-2">
                            <a href="/appointments/${a.id}/edit" class="px-3 py-1 rounded-lg bg-yellow-100 text-yellow-700 font-bold text-sm">Edit</a>
                            <button onclick="openModal(${a.id})" class="px-3 py-1 rounded-lg bg-red-100 text-red-700 font-bold text-sm">Delete</button>
                        </div>
                    </td>
                </tr>`;
            });

            document.getElementById('appointmentsTable').innerHTML = rows;
        });
});

function openModal(id) {
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteForm').action = '/appointments/' + id;
}

function closeModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}
</script>
@endsection