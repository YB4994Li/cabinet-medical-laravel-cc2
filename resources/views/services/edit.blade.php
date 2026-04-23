<h1>Edit Service</h1>

<form action="{{ route('services.update', $service->id) }}" method="POST">
    @csrf
    @method('PUT')

    <input type="text" name="name" value="{{ $service->name }}">
    <input type="text" name="description" value="{{ $service->description }}">
    <input type="number" name="price" value="{{ $service->price }}">
    <input type="number" name="duration" value="{{ $service->duration }}">

    <button type="submit">Update</button>
</form>