<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $users = User::paginate(5);
        return view('users.index', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'role' => 'required|in:patient,doctor,admin',
        ]);

        $user->update([
            'role' => $request->role,
        ]);

        return redirect()->route('users.index');
    }
}