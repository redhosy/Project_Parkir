<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingRate extends Model
{
    protected $fillable = [
        'vehicle_type',
        'duration_start_hour',
        'duration_end_hour',
        'rate',
        'is_flat_rate',
    ];

    protected $casts = [
        'is_flat_rate' => 'boolean',
        'rate' => 'decimal:2',
    ];
    
    /**
     * Get the parking slots associated with this rate.
     */
    public function parkingSlots()
    {
        return $this->hasMany(ParkingSlot::class);
    }

    /**
     * Get the appropriate rate for a given vehicle type and duration
     * 
     * @param string $vehicleType The type of vehicle (motor, mobil, truk)
     * @param float $hours The duration in hours
     * @param bool $includeBreakdown Whether to include a detailed breakdown of the calculation
     * @return array|float Returns the total cost or an array with cost and breakdown details
     */
    public static function getRateForDuration($vehicleType, $hours, $includeBreakdown = false)
    {
        $rates = self::where('vehicle_type', $vehicleType)
            ->orderBy('duration_start_hour', 'asc')
            ->get();
        
        $totalCost = 0;
        $remainingHours = $hours;
        $breakdown = [];
        
        // Check for flat rate first (typically applies to longer durations)
        $flatRate = $rates->where('is_flat_rate', true)
                          ->where('duration_start_hour', '<=', $hours)
                          ->sortByDesc('duration_start_hour')
                          ->first();
                          
        if ($flatRate && $hours >= $flatRate->duration_start_hour) {
            // If there's a flat rate that applies to this duration
            if ($includeBreakdown) {
                $breakdown[] = [
                    'type' => 'flat',
                    'duration' => $hours,
                    'rate' => $flatRate->rate,
                    'subtotal' => $flatRate->rate,
                    'description' => "Tarif flat untuk {$flatRate->duration_start_hour}+ jam"
                ];
            }
            return $includeBreakdown ? [
                'cost' => $flatRate->rate,
                'breakdown' => $breakdown
            ] : $flatRate->rate;
        }
        
        // Calculate hourly rates
        $hourlyRates = $rates->where('is_flat_rate', false)->sortBy('duration_start_hour');
        
        // Jika tidak ada tarif, kembalikan 0
        if ($hourlyRates->isEmpty()) {
            return $includeBreakdown ? ['cost' => 0, 'breakdown' => []] : 0;
        }
        
        foreach ($hourlyRates as $rate) {
            $durationLimit = $rate->duration_end_hour ?? PHP_FLOAT_MAX;
            
            // Hanya hitung jika jam durasi masuk dalam rentang tarif ini
            if ($rate->duration_start_hour <= $hours) {
                $hoursInThisRate = min(
                    $remainingHours,
                    $durationLimit - $rate->duration_start_hour
                );
                
                if ($hoursInThisRate > 0) {
                    // Pembulatan ke atas untuk menghitung tarif per jam
                    // Misalnya: 1 jam 15 menit dihitung sebagai 2 jam
                    $roundedHours = $hoursInThisRate;
                    if (!$rate->duration_end_hour && $remainingHours == $hoursInThisRate) {
                        // Untuk tarif akhir, bulatkan ke atas jika ada menit lebih
                        $wholeHours = floor($hoursInThisRate);
                        $minutes = ($hoursInThisRate - $wholeHours) * 60;
                        if ($minutes > 0) {
                            $roundedHours = $wholeHours + 1;
                        }
                    }
                    
                    $subtotal = $roundedHours * $rate->rate;
                    $totalCost += $subtotal;
                    
                    if ($includeBreakdown) {
                        // Format angka dengan 1 desimal jika ada nilai desimal
                        $formattedDuration = $hoursInThisRate == (int)$hoursInThisRate ? 
                            (int)$hoursInThisRate : 
                            number_format($hoursInThisRate, 1);
                            
                        $description = $rate->duration_end_hour 
                            ? "Jam {$rate->duration_start_hour}-{$rate->duration_end_hour}: {$formattedDuration} jam × Rp " . number_format($rate->rate, 0, ',', '.')
                            : "Setelah jam {$rate->duration_start_hour}: {$formattedDuration} jam × Rp " . number_format($rate->rate, 0, ',', '.');
                            
                        // Tampilkan jika dibulatkan
                        if ($roundedHours > $hoursInThisRate) {
                            $description .= " (dibulatkan ke atas)";
                        }
                        
                        $breakdown[] = [
                            'type' => 'hourly',
                            'duration' => $hoursInThisRate,
                            'rate' => $rate->rate,
                            'rounded_hours' => $roundedHours,
                            'subtotal' => $subtotal,
                            'description' => $description
                        ];
                    }
                    
                    $remainingHours -= $hoursInThisRate;
                }
            }
            
            if ($remainingHours <= 0) break;
        }
        
        return $includeBreakdown ? [
            'cost' => $totalCost,
            'breakdown' => $breakdown
        ] : $totalCost;
    }
}
