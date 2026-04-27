<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class DoctorController extends Controller
{
    public function index()
    {
        $this->authorizeAdmin();

        $doctors = User::with('services')
            ->where('role', 'doctor')
            ->orderBy('name')
            ->paginate(8);

        return view('doctors.index', compact('doctors'));
    }

    public function create()
    {
        $this->authorizeAdmin();

        $services = Service::orderBy('name')->get();

        return view('doctors.create', compact('services'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate($this->doctorRules());

        $doctor = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'doctor',
        ]);

        $doctor->services()->sync($validated['services']);

        return redirect()->route('doctors.index');
    }

    public function edit(User $doctor)
    {
        $this->authorizeAdmin();
        $this->ensureDoctor($doctor);

        $doctor->load('services');
        $services = Service::orderBy('name')->get();

        return view('doctors.edit', compact('doctor', 'services'));
    }

    public function update(Request $request, User $doctor)
    {
        $this->authorizeAdmin();
        $this->ensureDoctor($doctor);

        $validated = $request->validate($this->doctorRules($doctor));

        $doctorData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $doctorData['password'] = Hash::make($validated['password']);
        }

        $doctor->update($doctorData);
        $doctor->services()->sync($validated['services']);

        return redirect()->route('doctors.index');
    }

    private function doctorRules(?User $doctor = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($doctor?->id),
            ],
            'password' => [
                $doctor ? 'nullable' : 'required',
                'confirmed',
                Password::defaults(),
            ],
            'services' => ['required', 'array', 'min:1'],
            'services.*' => ['integer', Rule::exists('services', 'id')],
        ];
    }

    private function authorizeAdmin(): void
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
    }

    private function ensureDoctor(User $doctor): void
    {
        if ($doctor->role !== 'doctor') {
            abort(404);
        }
    }
}
