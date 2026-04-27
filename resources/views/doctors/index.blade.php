@extends('layouts.main')

@section('content')
<div class="flex justify-between items-start mb-8">
    <div>
        <h1 class="text-4xl font-extrabold text-slate-900">Doctors</h1>
        <p class="text-slate-500 mt-2">Create doctor accounts and assign medical services.</p>
    </div>

    <a href="{{ route('doctors.create') }}"
       class="bg-blue-700 text-white px-5 py-3 rounded-xl font-bold shadow-sm hover:bg-blue-800">
        + Add Doctor
    </a>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <table class="w-full">
        <thead class="bg-slate-100 text-slate-600 text-xs uppercase">
            <tr>
                <th class="text-left p-4">Doctor</th>
                <th class="text-left p-4">Email</th>
                <th class="text-left p-4">Services</th>
                <th class="text-left p-4">Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse($doctors as $doctor)
                <tr class="border-t border-slate-100 hover:bg-slate-50">
                    <td class="p-4 font-bold text-slate-900">{{ $doctor->name }}</td>
                    <td class="p-4 text-slate-600">{{ $doctor->email }}</td>
                    <td class="p-4">
                        <div class="flex flex-wrap gap-2">
                            @forelse($doctor->services as $service)
                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold">
                                    {{ $service->name }}
                                </span>
                            @empty
                                <span class="text-sm text-slate-500">No services assigned</span>
                            @endforelse
                        </div>
                    </td>
                    <td class="p-4">
                        <a href="{{ route('doctors.edit', $doctor->id) }}"
                           class="px-3 py-1 rounded-lg bg-yellow-100 text-yellow-700 font-bold text-sm">
                            Edit
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-6 text-center text-slate-500">
                        No doctors found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="p-6">
        {{ $doctors->links() }}
    </div>
</div>
@endsection
