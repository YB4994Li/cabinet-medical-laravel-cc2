<?php

use Illuminate\Support\Facades\Route;
use App\Models\Appointment;
use Illuminate\Http\Request;

Route::get('/appointments', function () {
    return Appointment::with(['patient', 'doctor', 'service'])->get();
});

Route::post('/appointments', function (Request $request) {
    return Appointment::create($request->all());
});