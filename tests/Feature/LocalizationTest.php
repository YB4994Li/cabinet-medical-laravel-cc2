<?php

use App\Models\User;

test('guests can switch auth pages to spanish', function () {
    $this->from('/login')
        ->get(route('language.switch', ['locale' => 'es']))
        ->assertRedirect('/login')
        ->assertSessionHas('locale', 'es');

    $this->withSession(['locale' => 'es'])
        ->get(route('login'))
        ->assertOk()
        ->assertSee('Bienvenido de nuevo')
        ->assertSee('Ingles')
        ->assertSee('Espanol');
});

test('authenticated navbar uses the selected language', function () {
    $user = User::factory()->create(['role' => 'patient']);

    $this->actingAs($user)
        ->withSession(['locale' => 'es'])
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Citas')
        ->assertSee('Servicios')
        ->assertSee('Cerrar sesion');
});

test('unsupported languages are rejected', function () {
    $this->get('/language/fr')->assertNotFound();
});
