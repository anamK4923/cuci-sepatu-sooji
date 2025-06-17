<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::all();
        return view('services.services-admin', compact('services'));
    }

    /**
     * Display a listing of services for customers
     */
    public function indexMember()
    {
        $services = Service::orderBy('name')->get();

        return view('member.services.index', compact('services'));
    }

    /**
     * Show the specified service
     */
    public function show(Service $service)
    {
        return view('member.services.show', compact('service'));
    }

    // public function indexAdmin(): View
    // {

    //     return view('services.services-admin');
    // }

    // public function indexMember(): View
    // {
    //     return view('services.services-member');
    // }

    public function create()
    {
        return view('services.services-admin-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        Service::create($data);

        return redirect()->route('services.admin')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit(Service $service)
    {
        return view('services.services-admin-edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // Hapus file lama jika ada
            if ($service->image && Storage::disk('public')->exists($service->image)) {
                Storage::disk('public')->delete($service->image);
            }

            $data['image'] = $request->file('image')->store('services', 'public');
        }

        $service->update($data);

        return redirect()->route('services.admin')->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('services.admin')->with('success', 'Layanan berhasil dihapus.');
    }
}
