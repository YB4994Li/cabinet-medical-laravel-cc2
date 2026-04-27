<?php

use App\Mail\AppointmentCreatedMail;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use App\Notifications\AppointmentStatusUpdatedNotification;
use App\Notifications\NewServiceNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

test('doctor receives an account notification when an appointment is created', function () {
    Mail::fake();

    $patient = User::factory()->create(['role' => 'patient']);
    $doctor = User::factory()->create(['role' => 'doctor']);
    $service = Service::factory()->create();
    $doctor->services()->attach($service);

    $this->actingAs($patient)->post(route('appointments.store'), [
        'doctor_id' => $doctor->id,
        'service_id' => $service->id,
        'appointment_date' => '2026-05-01',
        'appointment_time' => '10:30',
        'notes' => 'Initial appointment',
    ])->assertRedirect(route('appointments.index'));

    $notification = $doctor->notifications()->first();

    expect($notification)->not->toBeNull();
    expect($notification->data['title'])->toBe('New appointment assigned');
    expect($notification->data['message'])->toContain($patient->name);

    Mail::assertSent(AppointmentCreatedMail::class);
});

test('patient is notified in account and email when doctor confirms appointment', function () {
    Notification::fake();

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

    $this->actingAs($doctor)
        ->put(route('appointments.updateStatus', $appointment), ['status' => 'confirmed'])
        ->assertRedirect(route('appointments.index'));

    Notification::assertSentTo(
        $patient,
        AppointmentStatusUpdatedNotification::class,
        fn ($notification, $channels) => in_array('database', $channels)
            && in_array('mail', $channels)
    );
});

test('patients and doctors receive account notifications when admin adds a service', function () {
    Notification::fake();

    $admin = User::factory()->create(['role' => 'admin']);
    $patient = User::factory()->create(['role' => 'patient']);
    $doctor = User::factory()->create(['role' => 'doctor']);

    $this->actingAs($admin)->post(route('services.store'), [
        'name' => 'Nutrition Consultation',
        'description' => 'Guided nutrition appointment',
        'price' => 250,
        'duration' => 30,
    ])->assertRedirect(route('services.index'));

    Notification::assertSentTo($patient, NewServiceNotification::class);
    Notification::assertSentTo($doctor, NewServiceNotification::class);
    Notification::assertNotSentTo($admin, NewServiceNotification::class);
});
