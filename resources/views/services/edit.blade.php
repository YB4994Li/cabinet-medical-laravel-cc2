@extends('layouts.main')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-extrabold text-slate-900">Edit Service</h1>
    <p class="text-slate-500 mt-2">Update medical service information.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <form action="{{ route('services.update', $service->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-5">
                <label class="block text-sm font-bold text-slate-600 mb-2">Service Name</label>
                <input type="text" name="name" value="{{ $service->name }}"
                       class="w-full border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-5">
                <label class="block text-sm font-bold text-slate-600 mb-2">Description</label>
                <textarea name="description"
                          class="w-full border border-slate-200 rounded-xl p-3 h-28 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $service->description }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-2">Price</label>
                    <input type="number" name="price" value="{{ $service->price }}"
                           class="w-full border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-2">Duration (minutes)</label>
                    <input type="number" name="duration" value="{{ $service->duration }}"
                           class="w-full border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="flex justify-between items-center">
                <a href="{{ route('services.index') }}" class="text-slate-500 font-semibold">Cancel</a>

                <button type="submit"
                        class="bg-blue-700 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-800">
                    Update Service
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <h3 class="text-lg font-bold mb-4">Service Info</h3>
        <p class="text-sm text-slate-500">Update the service name, price, duration and description.</p>
        <p class="text-sm text-slate-500 mt-6">💡 Keep the service data clear for appointment booking.</p>
    </div>
</div>
@endsection