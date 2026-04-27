<?php

use App\Models\Service;
use App\Models\User;

test('admin can create a doctor and assign services', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $cardiology = Service::factory()->create(['name' => 'Cardiology']);
    $radiology = Service::factory()->create(['name' => 'Radiology']);

    $this->actingAs($admin)->post(route('doctors.store'), [
        'name' => 'Dr. Sara Smith',
        'email' => 'sara.smith@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'services' => [$cardiology->id, $radiology->id],
    ])->assertRedirect(route('doctors.index'));

    $doctor = User::where('email', 'sara.smith@example.com')->first();

    expect($doctor)->not->toBeNull();
    expect($doctor->role)->toBe('doctor');

    $this->assertDatabaseHas('doctor_service', [
        'doctor_id' => $doctor->id,
        'service_id' => $cardiology->id,
    ]);

    $this->assertDatabaseHas('doctor_service', [
        'doctor_id' => $doctor->id,
        'service_id' => $radiology->id,
    ]);

    $this->actingAs($admin)
        ->get(route('doctors.index'))
        ->assertOk()
        ->assertSee('Dr. Sara Smith')
        ->assertSee('Cardiology')
        ->assertSee('Radiology');
});

test('admin can update doctor services', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $cardiology = Service::factory()->create(['name' => 'Cardiology']);
    $dermatology = Service::factory()->create(['name' => 'Dermatology']);
    $doctor = User::factory()->create([
        'name' => 'Dr. Original',
        'role' => 'doctor',
    ]);

    $doctor->services()->attach($cardiology);

    $this->actingAs($admin)->put(route('doctors.update', $doctor), [
        'name' => 'Dr. Updated',
        'email' => $doctor->email,
        'services' => [$dermatology->id],
    ])->assertRedirect(route('doctors.index'));

    $doctor->refresh();

    expect($doctor->name)->toBe('Dr. Updated');

    $this->assertDatabaseMissing('doctor_service', [
        'doctor_id' => $doctor->id,
        'service_id' => $cardiology->id,
    ]);

    $this->assertDatabaseHas('doctor_service', [
        'doctor_id' => $doctor->id,
        'service_id' => $dermatology->id,
    ]);
});

test('non admins cannot manage doctors', function () {
    $patient = User::factory()->create(['role' => 'patient']);

    $this->actingAs($patient)
        ->get(route('doctors.index'))
        ->assertForbidden();

    $this->actingAs($patient)
        ->get(route('doctors.create'))
        ->assertForbidden();
});
