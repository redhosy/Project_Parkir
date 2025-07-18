@props(['slot'])

<div class="parking-slot {{ $slot->status }}" data-bs-toggle="tooltip" 
     title="Slot {{ $slot->code }} | {{ ucfirst($slot->type) }} | {{ ucfirst($slot->status) }}">
    <div class="slot-code">{{ $slot->code }}</div>
    <div class="slot-type">{{ strtoupper(substr($slot->type, 0, 1)) }}</div>
</div>

<style>
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
    
    .parking-slot.maintenance {
        background-color: #e9ecef;
        border: 1px solid #dee2e6;
    }
    .parking-slot.maintenance::before {
        background-color: var(--secondary);
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