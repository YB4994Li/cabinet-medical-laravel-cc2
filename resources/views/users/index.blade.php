@extends('layouts.main')

@section('content')
<div class="flex justify-between items-start mb-8">
    <div>
        <h1 class="text-4xl font-extrabold text-slate-900">Users Management</h1>
        <p class="text-slate-500 mt-2">Manage user roles and permissions.</p>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <table class="w-full">
        <thead class="bg-slate-100 text-slate-600 text-xs uppercase">
            <tr>
                <th class="text-left p-4">Name</th>
                <th class="text-left p-4">Email</th>
                <th class="text-left p-4">Current Role</th>
                <th class="text-left p-4">Change Role</th>
            </tr>
        </thead>

        <tbody>
            @foreach($users as $user)
            <tr class="border-t border-slate-100 hover:bg-slate-50">
                <td class="p-4 font-bold">{{ $user->name }}</td>
                <td class="p-4 text-slate-600">{{ $user->email }}</td>
                <td class="p-4">
                    @if($user->role === 'admin')
                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold uppercase">
                            ADMIN
                        </span>
                    @elseif($user->role === 'doctor')
                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold uppercase">
                            DOCTOR
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold uppercase">
                            PATIENT
                        </span>
                    @endif
                </td>
                <td class="p-4">
                    <form action="{{ route('users.updateRole', $user->id) }}" method="POST" class="flex gap-2">
                        @csrf
                        @method('PUT')

                        <select name="role"
                        class="border border-slate-200 rounded-xl px-4 py-2 pr-8 appearance-none bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="patient" {{ $user->role == 'patient' ? 'selected' : '' }}>Patient</option>
                            <option value="doctor" {{ $user->role == 'doctor' ? 'selected' : '' }}>Doctor</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>

                        <button class="bg-blue-700 text-white px-4 py-2 rounded-xl font-bold">
                            Update
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="p-6">
        {{ $users->links() }}
    </div>
</div>
@endsection