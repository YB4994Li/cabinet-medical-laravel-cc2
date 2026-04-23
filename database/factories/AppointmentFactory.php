<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    public function definition(): array
    {
        $patients = User::where('role', 'patient')->pluck('id')->toArray();
        $doctors = User::where('role', 'doctor')->pluck('id')->toArray();
        $services = Service::pluck('id')->toArray();

        return [
            'patient_id' => fake()->randomElement($patients),
            'doctor_id' => fake()->randomElement($doctors),
            'service_id' => fake()->randomElement($services),
            'appointment_date' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'appointment_time' => fake()->time(),
            'notes' => fake()->sentence(),
            'status' => fake()->randomElement(['pending', 'confirmed', 'cancelled']),
        ];
    }
}