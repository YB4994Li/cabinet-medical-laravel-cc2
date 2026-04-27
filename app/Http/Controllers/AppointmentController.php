<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Mail\AppointmentCreatedMail;
use App\Notifications\AppointmentAssignedNotification;
use App\Notifications\AppointmentStatusUpdatedNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $appointmentsQuery = Appointment::query();

        if ($user->role === 'doctor') {
            $appointmentsQuery->where('doctor_id', $user->id);
        } elseif ($user->role === 'patient') {
            $appointmentsQuery->where('patient_id', $user->id);
        }

        $statusCounts = (clone $appointmentsQuery)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $appointmentStats = [
            'total' => (clone $appointmentsQuery)->count(),
            'confirmed' => (int) ($statusCounts['confirmed'] ?? 0),
            'pending' => (int) ($statusCounts['pending'] ?? 0),
            'cancelled' => (int) ($statusCounts['cancelled'] ?? 0),
        ];

        $appointments = $appointmentsQuery
            ->with(['patient', 'doctor', 'service'])
            ->paginate(5);

        return view('appointments.index', compact('appointments', 'appointmentStats'));
    }

    public function create()
    {
        $this->authorizeAppointmentCreation();

        $currentUser = auth()->user();
        $patients = $currentUser->role === 'patient'
            ? collect([$currentUser])
            : User::where('role', 'patient')->orderBy('name')->get();
        $doctors = User::with('services')->where('role', 'doctor')->orderBy('name')->get();
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

        $appointment->doctor->notify(new AppointmentAssignedNotification($appointment));

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
        $doctors = User::with('services')->where('role', 'doctor')->orderBy('name')->get();
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

        if ($user->role !== 'doctor') {
            abort(403);
        }

        if ($appointment->doctor_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $oldStatus = $appointment->status;

        $appointment->update([
            'status' => $validated['status'],
        ]);

        if ($oldStatus !== $validated['status'] && in_array($validated['status'], ['confirmed', 'cancelled'])) {
            $appointment->refresh()->load(['patient', 'doctor', 'service']);

            $appointment->patient->notify(new AppointmentStatusUpdatedNotification(
                $appointment,
                $user,
                $user->role === 'doctor'
            ));
        }

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
        if (auth()->user()->role !== 'patient') {
            abort(403);
        }
    }
}
