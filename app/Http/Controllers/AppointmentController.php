<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Mail\AppointmentCreatedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $appointments = Appointment::with(['patient', 'doctor', 'service'])
                ->paginate(5);
        } elseif ($user->role === 'doctor') {
            $appointments = Appointment::with(['patient', 'doctor', 'service'])
                ->where('doctor_id', $user->id)
                ->paginate(5);
        } else {
            $appointments = Appointment::with(['patient', 'doctor', 'service'])
                ->where('patient_id', $user->id)
                ->paginate(5);
        }

        return view('appointments.index', compact('appointments'));
    }

    public function create()
    {
        $this->authorizeAppointmentCreation();

        $currentUser = auth()->user();
        $patients = $currentUser->role === 'patient'
            ? collect([$currentUser])
            : User::where('role', 'patient')->orderBy('name')->get();
        $doctors = User::where('role', 'doctor')->orderBy('name')->get();
        $services = Service::orderBy('name')->get();

        return view('appointments.create', compact('currentUser', 'patients', 'doctors', 'services'));
    }

    public function store(Request $request)
    {
        $this->authorizeAppointmentCreation();

        $currentUser = auth()->user();

        if ($currentUser->role === 'patient') {
            $request->merge(['patient_id' => $currentUser->id]);
        }

        $appointment = Appointment::create($request->validate($this->appointmentRules()));

        $appointment->load(['patient', 'doctor', 'service']);

        Mail::to($appointment->patient->email)
            ->send(new AppointmentCreatedMail($appointment));

        return redirect()->route('appointments.index');
    }

    public function edit(Appointment $appointment)
    {
        $this->authorizeAppointmentAccess($appointment);

        $currentUser = auth()->user();
        $patients = $currentUser->role === 'patient'
            ? collect([$currentUser])
            : User::where('role', 'patient')->orderBy('name')->get();
        $doctors = User::where('role', 'doctor')->orderBy('name')->get();
        $services = Service::orderBy('name')->get();

        return view('appointments.edit', compact('appointment', 'currentUser', 'patients', 'doctors', 'services'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $this->authorizeAppointmentAccess($appointment);

        $currentUser = auth()->user();

        if ($currentUser->role === 'patient') {
            $request->merge(['patient_id' => $currentUser->id]);
        }

        $appointment->update($request->validate($this->appointmentRules()));
        return redirect()->route('appointments.index');
    }

    public function destroy(Appointment $appointment)
    {
        $this->authorizeAppointmentAccess($appointment);

        $appointment->delete();
        return redirect()->route('appointments.index');
    }

    public function search(Request $request)
    {
        $user = auth()->user();
        $query = $request->q;

        $appointments = Appointment::with(['patient', 'doctor', 'service']);

        if ($user->role === 'doctor') {
            $appointments->where('doctor_id', $user->id);
        } elseif ($user->role === 'patient') {
            $appointments->where('patient_id', $user->id);
        }

        if (!empty($query)) {
            $appointments->where(function ($q) use ($query) {
                $q->whereHas('patient', function ($p) use ($query) {
                    $p->where('name', 'like', "%{$query}%");
                })
                ->orWhereHas('doctor', function ($d) use ($query) {
                    $d->where('name', 'like', "%{$query}%");
                })
                ->orWhereHas('service', function ($s) use ($query) {
                    $s->where('name', 'like', "%{$query}%");
                });
            });
        }

        return response()->json($appointments->get());
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $user = auth()->user();

        if ($user->role === 'doctor' && $appointment->doctor_id !== $user->id) {
            abort(403);
        }

        if (!in_array($user->role, ['admin', 'doctor'])) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $appointment->update([
            'status' => $request->status,
        ]);

        return redirect()->route('appointments.index');
    }

    private function appointmentRules(): array
    {
        return [
            'patient_id' => [
                'required',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'patient')),
            ],
            'doctor_id' => [
                'required',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'doctor')),
            ],
            'service_id' => ['required', Rule::exists('services', 'id')],
            'appointment_date' => ['required', 'date'],
            'appointment_time' => ['required'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    private function authorizeAppointmentAccess(Appointment $appointment): void
    {
        $user = auth()->user();

        if ($user->role === 'patient' && $appointment->patient_id !== $user->id) {
            abort(403);
        }

        if ($user->role === 'doctor' && $appointment->doctor_id !== $user->id) {
            abort(403);
        }
    }

    private function authorizeAppointmentCreation(): void
    {
        if (auth()->user()->role === 'doctor') {
            abort(403);
        }
    }
}
