<?php

use App\Mail\AppointmentCreatedMail;
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
