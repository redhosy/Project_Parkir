<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParkingSlot;

class ParkingSlotController extends Controller
{
    /**
     * Menampilkan semua slot parkir
     */
    public function index()
    {
        $slots = ParkingSlot::all();
        return response()->json($slots);
    }

    /**
     * Menambahkan slot baru (khusus admin)
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_slot' => 'required|unique:parking_slots,kode_slot',
        ]);

        $slot = ParkingSlot::create([
            'kode_slot' => $request->kode_slot,
            'status' => 'kosong'
        ]);

        return response()->json([
            'message' => 'Slot parkir berhasil ditambahkan',
            'data' => $slot
        ], 201);
    }

    /**
     * Mengubah status slot (kosong/terisi)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:kosong,terisi'
        ]);

        $slot = ParkingSlot::findOrFail($id);
        $slot->status = $request->status;
        $slot->save();

        return response()->json([
            'message' => 'Status slot diperbarui',
            'data' => $slot
        ]);
    }

    /**
     * Hapus slot parkir (opsional)
     */
    public function destroy($id)
    {
        $slot = ParkingSlot::findOrFail($id);
        $slot->delete();

        return response()->json([
            'message' => 'Slot parkir dihapus'
        ]);
    }
}
