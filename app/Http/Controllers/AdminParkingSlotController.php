<?php

namespace App\Http\Controllers;

use App\Models\ParkingSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminParkingSlotController extends Controller
{
    /**
     * Menampilkan daftar slot parkir dengan filter dan paginasi.
     */
    public function index(Request $request)
    {
        $query = ParkingSlot::with('parkingRate');

        // Filter berdasarkan kode slot
        if ($request->has('search_code') && $request->search_code != '') {
            $query->where('code', 'like', '%' . $request->search_code . '%');
        }

        // Filter berdasarkan jenis kendaraan
        if ($request->has('filter_type') && $request->filter_type != '') {
            $query->where('type', $request->filter_type);
        }

        // Filter berdasarkan status
        if ($request->has('filter_status') && $request->filter_status != '') {
            $query->where('status', $request->filter_status);
        }

        // Get parking rates for modals
        $parkingRates = \App\Models\ParkingRate::orderBy('vehicle_type')->orderBy('duration_start_hour')->get();
        $groupedRates = $parkingRates->groupBy('vehicle_type');

        $slots = $query->orderBy('code')->paginate(10);
        return view('admin.parking_slots.index', compact('slots', 'groupedRates'));
    }

    /**
     * Menyimpan slot parkir baru.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:parking_slots,code|string|max:10',
            'type' => 'required|in:motor,mobil,truk',
            'parking_rate_id' => 'required|exists:parking_rates,id',
            'area' => 'required|in:A,B,C',
            'location_description' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validasi gagal.', 'errors' => $validator->errors()], 422);
        }

        // Verify that the vehicle type matches the selected parking rate's vehicle type
        $parkingRate = \App\Models\ParkingRate::findOrFail($request->parking_rate_id);
        if ($parkingRate->vehicle_type !== $request->type) {
            return response()->json(['message' => 'Validasi gagal.', 'errors' => [
                'parking_rate_id' => ['Tarif parkir harus sesuai dengan jenis kendaraan']
            ]], 422);
        }
        
        $slot = ParkingSlot::create($request->all());

        return response()->json(['message' => 'Slot parkir berhasil ditambahkan!', 'slot' => $slot], 201);
    }

    /**
     * Mengupdate slot parkir.
     */
    public function update(Request $request, $id)
    {
        $slot = ParkingSlot::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:motor,mobil,truk',
            'parking_rate_id' => 'required|exists:parking_rates,id',
            'area' => 'required|in:A,B,C',
            'status' => 'required|in:available,booked,occupied,maintenance',
            'location_description' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validasi gagal.', 'errors' => $validator->errors()], 422);
        }

        // Verify that the vehicle type matches the selected parking rate's vehicle type
        $parkingRate = \App\Models\ParkingRate::findOrFail($request->parking_rate_id);
        if ($parkingRate->vehicle_type !== $request->type) {
            return response()->json(['message' => 'Validasi gagal.', 'errors' => [
                'parking_rate_id' => ['Tarif parkir harus sesuai dengan jenis kendaraan']
            ]], 422);
        }

        $slot->update($request->all());

        return response()->json(['message' => 'Slot parkir berhasil diperbarui!', 'slot' => $slot], 200);
    }

    /**
     * Menghapus slot parkir.
     */
    public function destroy($id)
    {
        $slot = ParkingSlot::findOrFail($id);
        $slot->delete();

        return response()->json(['message' => 'Slot parkir berhasil dihapus!'], 200);
    }

    /**
     * Show the add modal form.
     */
    public function showAddModal()
    {
        $parkingRates = \App\Models\ParkingRate::orderBy('vehicle_type')->orderBy('duration_start_hour')->get();
        $groupedRates = $parkingRates->groupBy('vehicle_type');
        
        return view('admin.parking_slots._add_modal', compact('groupedRates'));
    }

    /**
     * Show the edit modal form.
     */
    public function showEditModal($id)
    {
        $slot = ParkingSlot::with('parkingRate')->findOrFail($id);
        $parkingRates = \App\Models\ParkingRate::orderBy('vehicle_type')->orderBy('duration_start_hour')->get();
        $groupedRates = $parkingRates->groupBy('vehicle_type');
        
        return view('admin.parking_slots._edit_modal', compact('slot', 'groupedRates'));
    }
}