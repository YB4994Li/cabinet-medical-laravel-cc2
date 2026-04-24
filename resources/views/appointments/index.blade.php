<h1>Appointments</h1>

<input type="text" id="search" placeholder="Search appointments...">

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

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
document.getElementById('search').addEventListener('keyup', function() {
    let query = this.value;

    axios.get('/appointments/search?q=' + query)
        .then(response => {
            let data = response.data;
            let rows = '';

            data.forEach(a => {
                rows += `
                <tr>
                    <td>${a.patient.name}</td>
                    <td>${a.doctor.name}</td>
                    <td>${a.service.name}</td>
                    <td>${a.appointment_date}</td>
                    <td>${a.appointment_time}</td>
                    <td>${a.status}</td>
                    <td>
                        <a href="/appointments/${a.id}/edit">Edit</a>

                        <form action="/appointments/${a.id}" method="POST" style="display:inline;">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>`;
            });

            document.querySelector('table').innerHTML = `
                <tr>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Service</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>` + rows;
        });
});
</script>