<?php

use App\Models\User;

test('admin dashboard shows total doctors card', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    User::factory()->count(3)->create(['role' => 'doctor']);
    User::factory()->count(2)->create(['role' => 'patient']);

    $this->actingAs($admin)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Total Doctors')
        ->assertSee('3');
});
