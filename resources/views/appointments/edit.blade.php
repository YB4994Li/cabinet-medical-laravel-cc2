<h1>Edit Appointment</h1>

<form action="{{ route('appointments.update', $appointment->id) }}" method="POST">
    @csrf
    @method('PUT')

    <select name="patient_id">
        @foreach($patients as $p)
            <option value="{{ $p->id }}" {{ $p->id == $appointment->patient_id ? 'selected' : '' }}>
                {{ $p->name }}
            </option>
        @endforeach
    </select>

    <select name="doctor_id">
        @foreach($doctors as $d)
            <option value="{{ $d->id }}" {{ $d->id == $appointment->doctor_id ? 'selected' : '' }}>
                {{ $d->name }}
            </option>
        @endforeach
    </select>

    <select name="service_id">
        @foreach($services as $s)
            <option value="{{ $s->id }}" {{ $s->id == $appointment->service_id ? 'selected' : '' }}>
                {{ $s->name }}
            </option>
        @endforeach
    </select>

    <input type="date" name="appointment_date" value="{{ $appointment->appointment_date }}">
    <input type="time" name="appointment_time" value="{{ $appointment->appointment_time }}">
    <input type="text" name="notes" value="{{ $appointment->notes }}">

    <button type="submit">Update</button>
</form>