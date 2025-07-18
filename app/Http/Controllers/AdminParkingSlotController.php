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
        $query = ParkingSlot::query();

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }
        if ($request->has('area') && $request->area != '') {
            $query->where('area', $request->area);
        }

        $slots = $query->orderBy('code')->paginate(10);
        return view('admin.parking_slots.index', compact('slots'));
    }

    /**
     * Menyimpan slot parkir baru.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:parking_slots,code|string|max:10',
            'type' => 'required|in:motor,mobil,truk',
            'tarif' => 'required|numeric|min:0',
            'area' => 'required|in:A,B,C',
            'location_description' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validasi gagal.', 'errors' => $validator->errors()], 422);
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
            'tarif' => 'required|numeric|min:0',
            'area' => 'required|in:A,B,C',
            'status' => 'required|in:available,booked,occupied,maintenance',
            'location_description' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validasi gagal.', 'errors' => $validator->errors()], 422);
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
}