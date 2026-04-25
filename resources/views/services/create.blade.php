@extends('layouts.main')

@section('content')

<div class="mb-8">
    <h1 class="text-4xl font-extrabold text-slate-900">Add New Service</h1>
    <p class="text-slate-500 mt-2">Define a new medical service with pricing and duration.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <form action="{{ route('services.store') }}" method="POST">
            @csrf

            <div class="mb-5">
                <label class="block text-sm font-bold text-slate-600 mb-2">Service Name</label>
                <input type="text" name="name"
                       class="w-full border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="e.g. General Consultation">
            </div>

            <div class="mb-5">
                <label class="block text-sm font-bold text-slate-600 mb-2">Description</label>
                <textarea name="description"
                          class="w-full border border-slate-200 rounded-xl p-3 h-28 focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Describe the service..."></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-2">Price</label>
                    <input type="number" name="price"
                           class="w-full border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="0.00">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-2">Duration (minutes)</label>
                    <input type="number" name="duration"
                           class="w-full border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="30">
                </div>
            </div>

            <div class="flex justify-between items-center">
                <a href="{{ route('services.index') }}" class="text-slate-500 font-semibold">
                    Cancel
                </a>

                <button type="submit"
                        class="bg-blue-700 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-800">
                    Add Service
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <h3 class="text-lg font-bold mb-4">Service Preview</h3>

        <div class="bg-slate-100 rounded-xl p-4 text-slate-500 text-sm">
            Fill the form to preview your service.
        </div>

        <div class="mt-6 text-sm text-slate-500">
            <p>💡 Tip: Keep your description clear and short.</p>
        </div>
    </div>

</div>

@endsection