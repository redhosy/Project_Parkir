<?php

namespace App\Http\Controllers;

use App\Models\ParkingSlot;
use Illuminate\Http\Request;

class ParkingController extends Controller
{
    // Untuk API
    public function getParkingSlots()
    {
        $slots = ParkingSlot::where('status', 'available')->get();
        return response()->json($slots);
    }

    // Untuk Web
    public function index()
    {
        $slots = ParkingSlot::orderBy('code')->paginate(10);
        return view('parking.index', compact('slots'));
    }

    // Untuk Web - Edit Form
    public function edit($id)
    {
        $parking = ParkingSlot::findOrFail($id);
        return view('parking.edit', compact('parking'));
    }

    // Untuk Web - Update
    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required',
            'type' => 'required|in:motor,mobil,truk',
            'tarif' => 'required|numeric',
            'status' => 'required|in:available,booked,occupied,maintenance'
        ]);

        $parking = ParkingSlot::findOrFail($id);
        $parking->update($request->all());

        return redirect()->route('parking.index')
            ->with('success', 'Slot parkir berhasil diperbarui');
    }

    // Untuk Web - Delete
    public function destroy($id)
    {
        $parking = ParkingSlot::findOrFail($id);
        $parking->delete();

        return redirect()->route('parking.index')
            ->with('success', 'Slot parkir berhasil dihapus');
    }
}