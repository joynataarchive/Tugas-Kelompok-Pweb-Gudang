<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleLoan;
use Illuminate\Http\Request;

class VehicleLoanController extends Controller
{
    public function index(Request $request)
    {
        $user    = auth()->user();
        $isAdmin = $user->hasAnyRole(['Super Admin', 'Staff Gudang']);

        $loans = VehicleLoan::with(['vehicle', 'user'])
            ->when(!$isAdmin, fn($q) => $q->where('user_id', $user->id))
            ->when($request->search, fn($q) => $q->whereHas('vehicle', fn($v) =>
                $v->where('name', 'like', "%{$request->search}%")
                  ->orWhere('plate_number', 'like', "%{$request->search}%")
            ))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('vehicle-loans.index', compact('loans', 'isAdmin'));
    }

    public function create()
    {
        $vehicles = Vehicle::where('status', 'available')->orderBy('name')->get();
        return view('vehicle-loans.form', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id'  => ['required', 'exists:vehicles,id'],
            'purpose'     => ['required', 'string', 'max:255'],
            'borrowed_at' => ['required', 'date'],
            'notes'       => ['nullable', 'string', 'max:500'],
        ]);

        $vehicle = Vehicle::findOrFail($request->vehicle_id);

        if (!$vehicle->isAvailable()) {
            return back()->with('error', 'Kendaraan sudah tidak tersedia.');
        }

        VehicleLoan::create([
            'vehicle_id'  => $vehicle->id,
            'user_id'     => auth()->id(),
            'purpose'     => $request->purpose,
            'borrowed_at' => $request->borrowed_at,
            'notes'       => $request->notes,
            'status'      => 'borrowed',
        ]);

        $vehicle->update(['status' => 'borrowed']);

        return redirect()->route('vehicle-loans.index')->with('success', 'Peminjaman kendaraan berhasil dicatat.');
    }

    public function markReturned(Request $request, VehicleLoan $vehicleLoan)
    {
        if ($vehicleLoan->isReturned()) {
            return back()->with('error', 'Kendaraan sudah dikembalikan.');
        }

        $vehicleLoan->update([
            'status'      => 'returned',
            'returned_at' => now(),
        ]);

        $vehicleLoan->vehicle->update(['status' => 'available']);

        return back()->with('success', 'Kendaraan berhasil dikembalikan.');
    }
}
