@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4"><i class="fas fa-tachometer-alt me-2"></i>Dashboard Admin</h2>
    
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-uppercase">Total Slot</h6>
                            <h3>{{ $stats['total'] }}</h3>
                        </div>
                        <i class="fas fa-parking fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-uppercase">Tersedia</h6>
                            <h3>{{ $stats['available'] }}</h3>
                        </div>
                        <i class="fas fa-check-circle fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-uppercase">Dibooking</h6>
                            <h3>{{ $stats['booked'] }}</h3>
                        </div>
                        <i class="fas fa-calendar-check fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-uppercase">Terisi</h6>
                            <h3>{{ $stats['occupied'] }}</h3>
                        </div>
                        <i class="fas fa-car fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Daftar Booking Terkini -->
        <div class="col-md-6">
            <div class="card card-hover">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i> Booking Terkini</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Pemesan</th>
                                    <th>Slot</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentBookings as $booking)
                                <tr>
                                    <td>#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $booking->nama_pemesan }}</td>
                                    <td>{{ $booking->parkingSlot->code }}</td>
                                    <td>
                                        @if($booking->status == 'booked')
                                            <span class="badge bg-warning">Booked</span>
                                        @elseif($booking->status == 'checked_in')
                                            <span class="badge bg-primary">Check-in</span>
                                        @elseif($booking->status == 'checked_out')
                                            <span class="badge bg-success">Check-out</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Peta Slot Parkir -->
        <div class="col-md-6">
            <div class="card card-hover">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-map-marked-alt me-2"></i> Peta Slot Parkir</h5>
                </div>
                <div class="card-body">
                    <div class="parking-map">
                        @foreach($slots as $slot)
                        <div class="parking-slot {{ $slot->status }}" 
                             data-bs-toggle="tooltip" 
                             title="Slot {{ $slot->code }} | {{ ucfirst($slot->type) }} | {{ ucfirst($slot->status) }}">
                            <div class="slot-code">{{ $slot->code }}</div>
                            <div class="slot-type">{{ strtoupper(substr($slot->type, 0, 1)) }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .parking-map {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        gap: 10px;
    }
    
    .parking-slot {
        border-radius: 5px;
        padding: 10px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
    }
    
    .parking-slot::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
    }
    
    .parking-slot.available {
        background-color: #e8f5e9;
        border: 1px solid #c8e6c9;
    }
    .parking-slot.available::before {
        background-color: var(--success);
    }
    
    .parking-slot.booked {
        background-color: #fff8e1;
        border: 1px solid #ffe0b2;
    }
    .parking-slot.booked::before {
        background-color: var(--warning);
    }
    
    .parking-slot.occupied {
        background-color: #ffebee;
        border: 1px solid #ffcdd2;
    }
    .parking-slot.occupied::before {
        background-color: var(--danger);
    }
    
    .slot-code {
        font-weight: bold;
        font-size: 1.1rem;
    }
    
    .slot-type {
        font-size: 0.8rem;
        background-color: rgba(0,0,0,0.1);
        border-radius: 50%;
        width: 25px;
        height: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 5px auto 0;
    }
</style>

<script>
    // Enable tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endsection