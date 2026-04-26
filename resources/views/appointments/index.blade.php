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

    <div class="overflow-x-auto">
        <table class="min-w-[1200px] w-full">
            <thead class="bg-slate-100 text-slate-600 text-xs uppercase">
                <tr>
                    <th class="text-left p-4">Patient</th>
                    <th class="text-left p-4">Doctor</th>
                    <th class="text-left p-4">Service</th>
                    <th class="text-left p-4">Date</th>
                    <th class="text-left p-4">Time</th>
                    <th class="text-left p-4">Status</th>
                    <th class="text-left p-4">Actions</th>

                    @if(Auth::user()->role === 'doctor' || Auth::user()->role === 'admin')
                        <th class="text-left p-4">Update Status</th>
                    @endif
                </tr>
            </thead>

            <tbody id="appointmentsTable">
                @foreach($appointments as $a)
                <tr class="border-t border-slate-100 hover:bg-slate-50">
                    <td class="p-4 whitespace-nowrap font-bold text-slate-900">{{ $a->patient->name }}</td>
                    <td class="p-4 whitespace-nowrap text-slate-600">{{ $a->doctor->name }}</td>
                    <td class="p-4 whitespace-nowrap text-slate-600">{{ $a->service->name }}</td>
                    <td class="p-4 whitespace-nowrap text-slate-600">{{ $a->appointment_date }}</td>
                    <td class="p-4 whitespace-nowrap text-slate-600">{{ $a->appointment_time }}</td>

                    <td class="p-4 whitespace-nowrap">
                        @if($a->status == 'confirmed')
                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold">CONFIRMED</span>
                        @elseif($a->status == 'cancelled')
                            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold">CANCELLED</span>
                        @else
                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">PENDING</span>
                        @endif
                    </td>

                    <td class="p-4 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <button type="button"
                                    onclick="openAppointmentViewModal(
                                        @js($a->patient->name),
                                        @js($a->doctor->name),
                                        @js($a->service->name),
                                        @js($a->appointment_date),
                                        @js($a->appointment_time),
                                        @js($a->status),
                                        @js($a->notes)
                                    )"
                                    class="px-3 py-1 rounded-lg bg-blue-100 text-blue-700 font-bold text-sm">
                                View
                            </button>

                            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'patient')
                                <a href="{{ route('appointments.edit', $a->id) }}"
                                   class="px-3 py-1 rounded-lg bg-yellow-100 text-yellow-700 font-bold text-sm">
                                    Edit
                                </a>

                                <button type="button"
                                        onclick="openModal({{ $a->id }})"
                                        class="px-3 py-1 rounded-lg bg-red-100 text-red-700 font-bold text-sm">
                                    Delete
                                </button>
                            @endif
                        </div>
                    </td>

                    @if(Auth::user()->role === 'doctor' || Auth::user()->role === 'admin')
                    <td class="p-4 whitespace-nowrap">
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
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="p-6">
        {{ $appointments->links() }}
    </div>
</div>

<div id="deleteModal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">
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

<div id="appointmentViewModal"
     class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-6">
    <div class="bg-white rounded-2xl p-6 w-full max-w-3xl max-h-[90vh] overflow-y-auto shadow-xl animate-scale">
        <h2 class="text-2xl font-extrabold text-slate-900 mb-2">Appointment Details</h2>
        <p class="text-slate-500 mb-6 leading-relaxed">Full information about this medical appointment.</p>

        <div class="space-y-4">
            <div class="bg-slate-100 rounded-xl p-4">
                <p class="text-xs text-slate-500 font-bold uppercase">Patient</p>
                <p id="viewPatient" class="text-lg font-bold mt-1 text-slate-900"></p>
            </div>

            <div class="bg-slate-100 rounded-xl p-4">
                <p class="text-xs text-slate-500 font-bold uppercase">Doctor</p>
                <p id="viewDoctor" class="text-lg font-bold mt-1 text-slate-900"></p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-slate-100 rounded-xl p-4">
                    <p class="text-xs text-slate-500 font-bold uppercase">Service</p>
                    <p id="viewService" class="text-lg font-bold mt-1 text-slate-900"></p>
                </div>

                <div class="bg-slate-100 rounded-xl p-4">
                    <p class="text-xs text-slate-500 font-bold uppercase">Status</p>
                    <p id="viewStatus" class="text-lg font-bold mt-1 text-slate-900"></p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-slate-100 rounded-xl p-4">
                    <p class="text-xs text-slate-500 font-bold uppercase">Date</p>
                    <p id="viewDate" class="text-lg font-bold mt-1 text-slate-900"></p>
                </div>

                <div class="bg-slate-100 rounded-xl p-4">
                    <p class="text-xs text-slate-500 font-bold uppercase">Time</p>
                    <p id="viewTime" class="text-lg font-bold mt-1 text-slate-900"></p>
                </div>
            </div>

            <div class="bg-slate-100 rounded-xl p-4">
                <p class="text-xs text-slate-500 font-bold uppercase">Notes</p>
                <p id="viewNotes" class="text-slate-700 mt-1 leading-relaxed break-words whitespace-pre-wrap"></p>
            </div>
        </div>

        <div class="mt-6">
            <button type="button"
                    onclick="closeAppointmentViewModal()"
                    class="w-full bg-blue-700 text-white py-3 rounded-xl font-bold hover:bg-blue-800 transition">
                Close
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
const canUpdateStatus = @json(Auth::user()->role === 'doctor' || Auth::user()->role === 'admin');
const canEditDelete = @json(Auth::user()->role === 'admin' || Auth::user()->role === 'patient');
const csrfToken = @json(csrf_token());
const originalRows = document.getElementById('appointmentsTable').innerHTML;

function safe(value) {
    return value ?? '';
}

function statusBadge(status) {
    if (status === 'confirmed') {
        return `<span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold">CONFIRMED</span>`;
    }

    if (status === 'cancelled') {
        return `<span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold">CANCELLED</span>`;
    }

    return `<span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">PENDING</span>`;
}

function actionButtons(a) {
    let buttons = `
        <button type="button"
                onclick='openAppointmentViewModal(
                    ${JSON.stringify(safe(a.patient?.name))},
                    ${JSON.stringify(safe(a.doctor?.name))},
                    ${JSON.stringify(safe(a.service?.name))},
                    ${JSON.stringify(safe(a.appointment_date))},
                    ${JSON.stringify(safe(a.appointment_time))},
                    ${JSON.stringify(safe(a.status))},
                    ${JSON.stringify(safe(a.notes))}
                )'
                class="px-3 py-1 rounded-lg bg-blue-100 text-blue-700 font-bold text-sm">
            View
        </button>
    `;

    if (canEditDelete) {
        buttons += `
            <a href="/appointments/${a.id}/edit"
               class="px-3 py-1 rounded-lg bg-yellow-100 text-yellow-700 font-bold text-sm">
                Edit
            </a>

            <button type="button"
                    onclick="openModal(${a.id})"
                    class="px-3 py-1 rounded-lg bg-red-100 text-red-700 font-bold text-sm">
                Delete
            </button>
        `;
    }

    return buttons;
}

function statusUpdateForm(a) {
    if (!canUpdateStatus) return '';

    return `
        <td class="p-4 whitespace-nowrap">
            <form action="/appointments/${a.id}/status" method="POST" class="flex items-center gap-2">
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="_method" value="PUT">

                <select name="status" class="border border-slate-200 rounded-lg px-3 py-1 pr-8 bg-white text-sm font-bold">
                    <option value="pending" ${a.status === 'pending' ? 'selected' : ''}>Pending</option>
                    <option value="confirmed" ${a.status === 'confirmed' ? 'selected' : ''}>Confirmed</option>
                    <option value="cancelled" ${a.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                </select>

                <button type="submit" class="px-3 py-1 rounded-lg bg-green-100 text-green-700 font-bold text-sm">
                    Update
                </button>
            </form>
        </td>
    `;
}

document.getElementById('search').addEventListener('keyup', function() {
    let query = this.value.trim();

    if (query === '') {
        document.getElementById('appointmentsTable').innerHTML = originalRows;
        return;
    }

    axios.get('/appointments/search?q=' + encodeURIComponent(query))
        .then(response => {
            let rows = '';

            response.data.forEach(a => {
                rows += `
                <tr class="border-t border-slate-100 hover:bg-slate-50">
                    <td class="p-4 whitespace-nowrap font-bold text-slate-900">${safe(a.patient?.name)}</td>
                    <td class="p-4 whitespace-nowrap text-slate-600">${safe(a.doctor?.name)}</td>
                    <td class="p-4 whitespace-nowrap text-slate-600">${safe(a.service?.name)}</td>
                    <td class="p-4 whitespace-nowrap text-slate-600">${safe(a.appointment_date)}</td>
                    <td class="p-4 whitespace-nowrap text-slate-600">${safe(a.appointment_time)}</td>
                    <td class="p-4 whitespace-nowrap">${statusBadge(a.status)}</td>
                    <td class="p-4 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            ${actionButtons(a)}
                        </div>
                    </td>
                    ${statusUpdateForm(a)}
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

function openAppointmentViewModal(patient, doctor, service, date, time, status, notes) {
    document.getElementById('viewPatient').innerText = patient;
    document.getElementById('viewDoctor').innerText = doctor;
    document.getElementById('viewService').innerText = service;
    document.getElementById('viewDate').innerText = date;
    document.getElementById('viewTime').innerText = time;
    document.getElementById('viewStatus').innerText = status;
    document.getElementById('viewNotes').innerText = notes || 'No notes';

    document.getElementById('appointmentViewModal').classList.remove('hidden');
}

function closeAppointmentViewModal() {
    document.getElementById('appointmentViewModal').classList.add('hidden');
}

document.getElementById('appointmentViewModal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.add('hidden');
    }
});
</script>
@endsection