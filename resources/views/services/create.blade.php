<h1>Add Service</h1>

<form action="{{ route('services.store') }}" method="POST">
    @csrf

    <input type="text" name="name" placeholder="Name">
    <input type="text" name="description" placeholder="Description">
    <input type="number" name="price" placeholder="Price">
    <input type="number" name="duration" placeholder="Duration">

    <button type="submit">Save</button>
</form>