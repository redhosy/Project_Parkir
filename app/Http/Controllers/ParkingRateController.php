<?php

namespace App\Http\Controllers;

use App\Models\ParkingRate;
use Illuminate\Http\Request;

class ParkingRateController extends Controller
{
    public function index(Request $request)
    {
        // Query with ordering
        $query = ParkingRate::orderBy('vehicle_type')
            ->orderBy('duration_start_hour');
        
        // Filter by vehicle type if provided
        if ($request->has('filter_vehicle_type') && $request->filter_vehicle_type != '') {
            $query->where('vehicle_type', $request->filter_vehicle_type);
        }
        
        // Filter by rate type if provided
        if ($request->has('filter_rate_type') && $request->filter_rate_type != '') {
            $query->where('is_flat_rate', $request->filter_rate_type == 'flat' ? 1 : 0);
        }
        
        // Get paginated results (10 per page)
        $rates = $query->paginate(10);
        
        // Group rates by vehicle type for easier display
        $ratesByType = $rates->groupBy('vehicle_type');
        
        return view('admin.parking_rates.index', compact('rates', 'ratesByType'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_type' => 'required|in:motor,mobil,truk',
            'duration_start_hour' => 'required|integer|min:0',
            'duration_end_hour' => 'nullable|integer|gt:duration_start_hour',
            'rate' => 'required|numeric|min:0',
            'is_flat_rate' => 'boolean',
        ]);

        ParkingRate::create($validated);

        return redirect()->route('admin.parking_rates.index')
            ->with('success', 'Tarif parkir berhasil ditambahkan');
    }
    
    /**
     * Show a specific parking rate
     */
    public function show($id)
    {
        $rate = ParkingRate::findOrFail($id);
        return response()->json($rate);
    }

    public function update(Request $request, $id)
    {
        try {
            $rate = ParkingRate::findOrFail($id);
            
            $validated = $request->validate([
                'vehicle_type' => 'required|in:motor,mobil,truk',
                'duration_start_hour' => 'required|integer|min:0',
                'duration_end_hour' => 'nullable|integer|gt:duration_start_hour',
                'rate' => 'required|numeric|min:0',
                'is_flat_rate' => 'boolean',
            ]);

            // Convert is_flat_rate from string to boolean if needed
            if (isset($validated['is_flat_rate']) && is_string($validated['is_flat_rate'])) {
                $validated['is_flat_rate'] = $validated['is_flat_rate'] === '1' || 
                                            $validated['is_flat_rate'] === 'true' || 
                                            $validated['is_flat_rate'] === 'on';
            }
            
            $rate->update($validated);
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tarif parkir berhasil diperbarui',
                    'data' => $rate
                ]);
            }

            return redirect()->route('admin.parking_rates.index')
                ->with('success', 'Tarif parkir berhasil diperbarui');
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui tarif parkir: ' . $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal memperbarui tarif parkir: ' . $e->getMessage()]);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $rate = ParkingRate::findOrFail($id);
            $rate->delete();
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tarif parkir berhasil dihapus'
                ]);
            }
            
            return redirect()->route('admin.parking_rates.index')
                ->with('success', 'Tarif parkir berhasil dihapus');
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus tarif parkir: ' . $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors(['error' => 'Gagal menghapus tarif parkir: ' . $e->getMessage()]);
        }
    }
}
