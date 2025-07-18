@props(['status'])

@php
    $colors = [
        'available' => 'bg-green-500',
        'booked' => 'bg-yellow-500',
        'occupied' => 'bg-red-500',
        'maintenance' => 'bg-gray-500',
        'pending' => 'bg-blue-500',
        'active' => 'bg-purple-500',
        'completed' => 'bg-green-600',
        'cancelled' => 'bg-red-600',
    ];

    $texts = [
        'available' => 'Tersedia',
        'booked' => 'Dibooking',
        'occupied' => 'Terisi',
        'maintenance' => 'Maintenance',
        'pending' => 'Menunggu Check-in',
        'active' => 'Sedang Parkir',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];
@endphp

<span class="badge {{ $colors[$status] ?? 'bg-gray-500' }} text-white px-3 py-2 rounded-full text-xs font-semibold">
    {{ $texts[$status] ?? ucfirst($status) }}
</span>