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

            <button onclick="openModal({{ $a->id }})">Delete</button>
        </td>
    </tr>
    @endforeach
</table>

<!-- MODAL -->
<div id="deleteModal" style="display:none; position:fixed; top:30%; left:40%; background:white; padding:20px; border:1px solid black;">
    <p>Are you sure you want to delete this appointment?</p>

    <form id="deleteForm" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit">Yes Delete</button>
    </form>

    <button onclick="closeModal()">Cancel</button>
</div>

<!-- AXIOS -->
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
                        <button onclick="openModal(${a.id})">Delete</button>
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

<!-- MODAL JS -->
<script>
function openModal(id) {
    document.getElementById('deleteModal').style.display = 'block';

    let form = document.getElementById('deleteForm');
    form.action = '/appointments/' + id;
}

function closeModal() {
    document.getElementById('deleteModal').style.display = 'none';
}
</script>