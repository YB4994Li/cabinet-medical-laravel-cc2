<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Mail\AppointmentCreatedMail;
use Illuminate\Support\Facades\Mail;

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
        $patients = User::where('role', 'patient')->get();
        $doctors = User::where('role', 'doctor')->get();
        $services = Service::all();

        return view('appointments.create', compact('patients', 'doctors', 'services'));
    }

    public function store(Request $request)
    {
        $appointment = Appointment::create($request->all());

        $appointment->load(['patient', 'doctor', 'service']);

        Mail::to($appointment->patient->email)
            ->send(new AppointmentCreatedMail($appointment));

        return redirect()->route('appointments.index');
    }

    public function edit(Appointment $appointment)
    {
        $patients = User::where('role', 'patient')->get();
        $doctors = User::where('role', 'doctor')->get();
        $services = Service::all();

        return view('appointments.edit', compact('appointment', 'patients', 'doctors', 'services'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $appointment->update($request->all());
        return redirect()->route('appointments.index');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return redirect()->route('appointments.index');
    }

    public function search(Request $request)
    {
        $query = $request->q;

        $appointments = Appointment::with(['patient', 'doctor', 'service'])
            ->whereHas('patient', function ($q) use ($query) {
                $q->where('name', 'like', "%$query%");
            })
            ->orWhereHas('doctor', function ($q) use ($query) {
                $q->where('name', 'like', "%$query%");
            })
            ->get();

        return response()->json($appointments);
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
}