<h1>Add Appointment</h1>

<form action="{{ route('appointments.store') }}" method="POST">
    @csrf

    <label>Patient</label>
    <select name="patient_id">
        @foreach($patients as $p)
            <option value="{{ $p->id }}">{{ $p->name }}</option>
        @endforeach
    </select>

    <label>Doctor</label>
    <select name="doctor_id">
        @foreach($doctors as $d)
            <option value="{{ $d->id }}">{{ $d->name }}</option>
        @endforeach
    </select>

    <label>Service</label>
    <select name="service_id">
        @foreach($services as $s)
            <option value="{{ $s->id }}">{{ $s->name }}</option>
        @endforeach
    </select>

    <input type="date" name="appointment_date">
    <input type="time" name="appointment_time">
    <input type="text" name="notes" placeholder="Notes">

    <button type="submit">Save</button>
</form>