<h1>Appointments</h1>

<a href="{{ route('appointments.create') }}">Add Appointment</a>

<table border="1">
    <tr>
        <th>Patient</th>
        <th>Doctor</th>
        <th>Service</th>
        <th>Date</th>
        <th>Time</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>

    @foreach($appointments as $a)
    <tr>
        <td>{{ $a->patient->name }}</td>
        <td>{{ $a->doctor->name }}</td>
        <td>{{ $a->service->name }}</td>
        <td>{{ $a->appointment_date }}</td>
        <td>{{ $a->appointment_time }}</td>
        <td>{{ $a->status }}</td>
        <td>
            <a href="{{ route('appointments.edit', $a->id) }}">Edit</a>

            <form action="{{ route('appointments.destroy', $a->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>