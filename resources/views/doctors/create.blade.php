@extends('layouts.main')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-extrabold text-slate-900">Add Doctor</h1>
    <p class="text-slate-500 mt-2">Create a doctor account and choose the services they provide.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <form action="{{ route('doctors.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-2">Doctor Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('name')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('email')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-2">Password</label>
                    <input type="password" name="password" required
                           class="w-full border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('password')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-600 mb-3">Services</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($services as $service)
                        <label class="flex items-center gap-3 border border-slate-200 rounded-xl px-4 py-3 hover:bg-slate-50">
                            <input type="checkbox" name="services[]" value="{{ $service->id }}"
                                   @checked(in_array($service->id, old('services', [])))
                                   class="rounded border-slate-300 text-blue-700 focus:ring-blue-500">
                            <span class="font-semibold text-slate-700">{{ $service->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('services')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
                @error('services.*')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-between items-center">
                <a href="{{ route('doctors.index') }}" class="text-slate-500 font-semibold">Cancel</a>

                <button type="submit"
                        class="bg-blue-700 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-800">
                    Add Doctor
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <h3 class="text-lg font-bold mb-4">Info</h3>
        <p class="text-sm text-slate-500 leading-relaxed">
            Select one or more services that match the doctor's medical specialty.
        </p>
    </div>
</div>
@endsection
