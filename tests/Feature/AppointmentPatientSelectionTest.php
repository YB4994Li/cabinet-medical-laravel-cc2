<?php

use App\Mail\AppointmentCreatedMail;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

test('patients see their account name when creating an appointment', function () {
    $patient = User::factory()->create([
        'name' => 'Mohammed Patient',
        'role' => 'patient',
    ]);

    User::factory()->create([
        'name' => 'Other Patient',
        'role' => 'patient',
    ]);

    User::factory()->create(['role' => 'doctor']);
    Service::factory()->create();

    $response = $this->actingAs($patient)->get(route('appointments.create'));

    $response->assertOk();
    $response->assertSee('Mohammed Patient');
    $response->assertDontSee('Other Patient');
});

test('patients can only create appointments for their own account', function () {
    Mail::fake();

    $patient = User::factory()->create(['role' => 'patient']);
    $otherPatient = User::factory()->create(['role' => 'patient']);
    $doctor = User::factory()->create(['role' => 'doctor']);
    $service = Service::factory()->create();
    $doctor->services()->attach($service);

    $response = $this->actingAs($patient)->post(route('appointments.store'), [
        'patient_id' => $otherPatient->id,
        'doctor_id' => $doctor->id,
        'service_id' => $service->id,
        'appointment_date' => '2026-05-01',
        'appointment_time' => '10:30',
        'notes' => 'Annual checkup',
    ]);

    $response->assertRedirect(route('appointments.index'));

    $this->assertDatabaseHas('appointments', [
        'patient_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'service_id' => $service->id,
        'notes' => 'Annual checkup',
    ]);

    $this->assertDatabaseMissing('appointments', [
        'patient_id' => $otherPatient->id,
        'notes' => 'Annual checkup',
    ]);

    Mail::assertSent(AppointmentCreatedMail::class, function ($mail) use ($patient) {
        return $mail->appointment->patient_id === $patient->id;
    });
});

test('patients can only choose doctors assigned to the selected service', function () {
    Mail::fake();

    $patient = User::factory()->create(['role' => 'patient']);
    $cardiology = Service::factory()->create(['name' => 'Cardiology']);
    $radiology = Service::factory()->create(['name' => 'Radiology']);
    $cardiologyDoctor = User::factory()->create(['role' => 'doctor']);
    $radiologyDoctor = User::factory()->create(['role' => 'doctor']);

    $cardiologyDoctor->services()->attach($cardiology);
    $radiologyDoctor->services()->attach($radiology);

    $this->actingAs($patient)->post(route('appointments.store'), [
        'doctor_id' => $radiologyDoctor->id,
        'service_id' => $cardiology->id,
        'appointment_date' => '2026-05-01',
        'appointment_time' => '10:30',
    ])
        ->assertSessionHasErrors('doctor_id');

    $this->assertDatabaseCount('appointments', 0);

    $this->actingAs($patient)->post(route('appointments.store'), [
        'doctor_id' => $cardiologyDoctor->id,
        'service_id' => $cardiology->id,
        'appointment_date' => '2026-05-01',
        'appointment_time' => '10:30',
    ])
        ->assertRedirect(route('appointments.index'));

    $this->assertDatabaseHas('appointments', [
        'patient_id' => $patient->id,
        'doctor_id' => $cardiologyDoctor->id,
        'service_id' => $cardiology->id,
    ]);
});

test('doctors do not see appointment creation actions', function () {
    $doctor = User::factory()->create(['role' => 'doctor']);

    $response = $this->actingAs($doctor)->get(route('appointments.index'));

    $response->assertOk();
    $response->assertDontSee('+ Add Appointment');
    $response->assertDontSee('+ Add New Entry');
});

test('doctors cannot create appointments directly', function () {
    $doctor = User::factory()->create(['role' => 'doctor']);
    $patient = User::factory()->create(['role' => 'patient']);
    $service = Service::factory()->create();

    $this->actingAs($doctor)
        ->get(route('appointments.create'))
        ->assertForbidden();

    $this->actingAs($doctor)
        ->post(route('appointments.store'), [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'service_id' => $service->id,
            'appointment_date' => '2026-05-01',
            'appointment_time' => '10:30',
            'notes' => 'Doctor-created appointment',
        ])
        ->assertForbidden();

    $this->assertDatabaseCount('appointments', 0);
});

test('admins do not see appointment creation actions', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('appointments.index'))
        ->assertOk()
        ->assertDontSee('+ Add Appointment')
        ->assertDontSee('+ Add New Entry');
});

test('admins cannot create appointments directly', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $patient = User::factory()->create(['role' => 'patient']);
    $doctor = User::factory()->create(['role' => 'doctor']);
    $service = Service::factory()->create();

    $this->actingAs($admin)
        ->get(route('appointments.create'))
        ->assertForbidden();

    $this->actingAs($admin)
        ->post(route('appointments.store'), [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'service_id' => $service->id,
            'appointment_date' => '2026-05-01',
            'appointment_time' => '10:30',
            'notes' => 'Admin-created appointment',
        ])
        ->assertForbidden();

    $this->assertDatabaseCount('appointments', 0);
});

test('admins cannot update appointment status', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $patient = User::factory()->create(['role' => 'patient']);
    $doctor = User::factory()->create(['role' => 'doctor']);
    $service = Service::factory()->create();
    $appointment = Appointment::create([
        'patient_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'service_id' => $service->id,
        'appointment_date' => '2026-05-01',
        'appointment_time' => '10:30',
        'status' => 'pending',
    ]);

    $this->actingAs($admin)
        ->get(route('appointments.index'))
        ->assertOk()
        ->assertDontSee('Update Status');

    $this->actingAs($admin)
        ->put(route('appointments.updateStatus', $appointment), ['status' => 'confirmed'])
        ->assertForbidden();

    expect($appointment->refresh()->status)->toBe('pending');
});

test('appointment cards count all matching appointments across pagination', function () {
    $doctor = User::factory()->create(['role' => 'doctor']);
    $patient = User::factory()->create(['role' => 'patient']);
    $service = Service::factory()->create();

    foreach (['confirmed', 'confirmed', 'pending', 'pending', 'pending', 'cancelled', 'cancelled'] as $index => $status) {
        Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'service_id' => $service->id,
            'appointment_date' => '2026-05-'.str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT),
            'appointment_time' => '10:30',
            'status' => $status,
        ]);
    }

    $this->actingAs($doctor)
        ->get(route('appointments.index'))
        ->assertOk()
        ->assertSeeInOrder([
            'Total Appointments',
            '7',
            'Confirmed',
            '2',
            'Pending',
            '3',
            'Cancelled',
            '2',
        ]);
});
