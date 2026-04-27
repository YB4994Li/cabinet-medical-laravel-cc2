<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\User;
use App\Notifications\NewServiceNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::paginate(5);
        return view('services.index', compact('services'));
    }

    public function create()
    {
        $this->authorizeServiceManagement();

        return view('services.create');
    }

    public function store(Request $request)
    {
        $this->authorizeServiceManagement();

        $service = Service::create($request->validate($this->serviceRules()));

        Notification::send(
            User::whereIn('role', ['doctor', 'patient'])->get(),
            new NewServiceNotification($service)
        );

        return redirect()->route('services.index');
    }

    public function edit(Service $service)
    {
        $this->authorizeServiceManagement();

        return view('services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $this->authorizeServiceManagement();

        $service->update($request->validate($this->serviceRules()));
        return redirect()->route('services.index');
    }

    public function destroy(Service $service)
    {
        $this->authorizeServiceManagement();

        $service->delete();
        return redirect()->route('services.index');
    }

    private function serviceRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'duration' => ['nullable', 'integer', 'min:1'],
        ];
    }

    private function authorizeServiceManagement(): void
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
    }
}
