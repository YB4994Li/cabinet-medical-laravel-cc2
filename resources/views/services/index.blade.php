@extends('layouts.main')

@section('content')
<div class="flex justify-between items-start mb-8">
    <div>
        <h1 class="text-4xl font-extrabold text-slate-900">Services</h1>
        <p class="text-slate-500 mt-2">Manage medical services, pricing, and consultation durations.</p>
    </div>

    @if(Auth::user()->role === 'admin')
        <a href="{{ route('services.create') }}"
           class="bg-blue-700 text-white px-5 py-3 rounded-xl font-bold shadow-sm hover:bg-blue-800">
            + Add Service
        </a>
    @endif
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <p class="text-xs font-bold text-slate-500 uppercase">Total Services</p>
        <h2 class="text-4xl font-extrabold mt-3">{{ $services->total() }}</h2>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <p class="text-xs font-bold text-slate-500 uppercase">Average Duration</p>
        <h2 class="text-4xl font-extrabold mt-3">{{ round($services->avg('duration')) }} min</h2>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <p class="text-xs font-bold text-slate-500 uppercase">Average Price</p>
        <h2 class="text-4xl font-extrabold mt-3">{{ round($services->avg('price'), 2) }} DH</h2>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="p-6 flex justify-between items-center border-b border-slate-200">
        <div>
            <h2 class="text-2xl font-bold">Service Directory</h2>
            <p class="text-slate-500 text-sm mt-1">All available medical services.</p>
        </div>
    </div>

    <table class="w-full">
        <thead class="bg-slate-100 text-slate-600 text-xs uppercase">
            <tr>
                <th class="text-left p-4">Service Name</th>
                <th class="text-left p-4">Description</th>
                <th class="text-left p-4">Price</th>
                <th class="text-left p-4">Duration</th>
                <th class="text-left p-4">Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach($services as $service)
            <tr class="border-t border-slate-100 hover:bg-slate-50">
                <td class="p-4 font-bold text-slate-900">{{ $service->name }}</td>

                <td class="p-4 text-slate-600">
                    {{ Str::limit($service->description, 35) }}
                </td>

                <td class="p-4 font-semibold">{{ $service->price }} DH</td>
                <td class="p-4 text-slate-600">{{ $service->duration }} min</td>

                <td class="p-4">
                    <div class="flex gap-2">
                        <button onclick="openServiceViewModal(
                            @js($service->name),
                            @js($service->description),
                            @js($service->price),
                            @js($service->duration)
                        )"
                        class="px-3 py-1 rounded-lg bg-blue-100 text-blue-700 font-bold text-sm">
                            View
                        </button>

                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('services.edit', $service->id) }}"
                               class="px-3 py-1 rounded-lg bg-yellow-100 text-yellow-700 font-bold text-sm">
                                Edit
                            </a>

                            <button onclick="openServiceModal({{ $service->id }})"
                                    class="px-3 py-1 rounded-lg bg-red-100 text-red-700 font-bold text-sm">
                                Delete
                            </button>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="p-6">
        {{ $services->links() }}
    </div>
</div>

<div id="serviceDeleteModal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 w-96 shadow-xl">
        <h2 class="text-xl font-bold mb-2">Delete Service</h2>
        <p class="text-slate-500 mb-6">Are you sure you want to delete this service?</p>

        <form id="serviceDeleteForm" method="POST" class="flex gap-3">
            @csrf
            @method('DELETE')

            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-xl font-bold">
                Yes Delete
            </button>

            <button type="button" onclick="closeServiceModal()" class="bg-slate-100 px-4 py-2 rounded-xl font-bold">
                Cancel
            </button>
        </form>
    </div>
</div>

<div id="serviceViewModal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 w-[420px] shadow-xl animate-scale">
        <h2 id="viewServiceName" class="text-2xl font-extrabold text-slate-900 mb-3"></h2>

        <p id="viewServiceDescription" class="text-slate-600 mb-6 leading-relaxed"></p>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="bg-slate-100 rounded-xl p-4">
                <p class="text-xs text-slate-500 font-bold uppercase">Price</p>
                <p id="viewServicePrice" class="text-xl font-bold mt-1"></p>
            </div>

            <div class="bg-slate-100 rounded-xl p-4">
                <p class="text-xs text-slate-500 font-bold uppercase">Duration</p>
                <p id="viewServiceDuration" class="text-xl font-bold mt-1"></p>
            </div>
        </div>

        <button onclick="closeServiceViewModal()"
                class="w-full bg-blue-700 text-white py-3 rounded-xl font-bold hover:bg-blue-800">
            Close
        </button>
    </div>
</div>

<script>
function openServiceModal(id) {
    document.getElementById('serviceDeleteModal').classList.remove('hidden');
    document.getElementById('serviceDeleteForm').action = '/services/' + id;
}

function closeServiceModal() {
    document.getElementById('serviceDeleteModal').classList.add('hidden');
}

function openServiceViewModal(name, description, price, duration) {
    document.getElementById('viewServiceName').innerText = name;
    document.getElementById('viewServiceDescription').innerText = description;
    document.getElementById('viewServicePrice').innerText = price + ' DH';
    document.getElementById('viewServiceDuration').innerText = duration + ' min';

    document.getElementById('serviceViewModal').classList.remove('hidden');
}

function closeServiceViewModal() {
    document.getElementById('serviceViewModal').classList.add('hidden');
}

document.getElementById('serviceViewModal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.add('hidden');
    }
});
</script>
@endsection