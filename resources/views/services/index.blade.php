<h1>Services</h1>

<a href="{{ route('services.create') }}">Add Service</a>

<table border="1">
    <tr>
        <th>Name</th>
        <th>Description</th>
        <th>Price</th>
        <th>Duration</th>
        <th>Actions</th>
    </tr>

    @foreach($services as $service)
    <tr>
        <td>{{ $service->name }}</td>
        <td>{{ $service->description }}</td>
        <td>{{ $service->price }}</td>
        <td>{{ $service->duration }}</td>
        <td>
            <a href="{{ route('services.edit', $service->id) }}">Edit</a>

            <form action="{{ route('services.destroy', $service->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>