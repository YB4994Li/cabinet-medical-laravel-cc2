@extends('layouts.main')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-extrabold text-slate-900">Profile</h1>
    <p class="text-slate-500 mt-2">Manage your account information, avatar, and security settings.</p>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <div class="xl:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <div class="flex items-center gap-4">
                @if($user->profile_photo_url)
                    <img src="{{ $user->profile_photo_url }}"
                         alt="{{ $user->name }}"
                         class="w-20 h-20 rounded-full object-cover border border-slate-200">
                @else
                    <div class="w-20 h-20 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-3xl font-extrabold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif

                <div>
                    <h2 class="text-xl font-extrabold text-slate-900">{{ $user->name }}</h2>
                    <p class="text-sm text-slate-500">{{ $user->email }}</p>
                    <span class="inline-flex mt-3 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold uppercase">
                        {{ $user->role }}
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection
