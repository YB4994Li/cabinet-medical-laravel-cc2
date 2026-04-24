<h2>Appointment Confirmation</h2>

<p>Your appointment has been created successfully.</p>

<ul>
    <li>Patient: {{ $appointment->patient->name }}</li>
    <li>Doctor: {{ $appointment->doctor->name }}</li>
    <li>Service: {{ $appointment->service->name }}</li>
    <li>Date: {{ $appointment->appointment_date }}</li>
    <li>Time: {{ $appointment->appointment_time }}</li>
</ul>