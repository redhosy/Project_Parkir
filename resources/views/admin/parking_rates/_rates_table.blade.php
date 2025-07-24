<table class="w-full">
    <thead class="bg-gray-50 dark:bg-gray-700">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                Jenis Kendaraan
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                Durasi
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                Tarif
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                Tipe Tarif
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                Aksi
            </th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
        @forelse($rates as $rate)
            <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <td class="px-6 py-4 whitespace-nowrap">
                    @php
                        $vehicleTypeClass = match($rate->vehicle_type) {
                            'motor' => 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100',
                            'mobil' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
                            'truk' => 'bg-orange-100 text-orange-800 dark:bg-orange-800 dark:text-orange-100',
                            default => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100'
                        };
                        $vehicleTypeText = match($rate->vehicle_type) {
                            'motor' => 'Motor',
                            'mobil' => 'Mobil',
                            'truk' => 'Truk',
                            default => 'Tidak Diketahui'
                        };
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $vehicleTypeClass }}">
                        {{ $vehicleTypeText }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($rate->duration_end_hour)
                        {{ $rate->duration_start_hour }} - {{ $rate->duration_end_hour }} jam
                    @elseif($rate->is_flat_rate)
                        ≥ {{ $rate->duration_start_hour }} jam (Tarif flat)
                    @else
                        ≥ {{ $rate->duration_start_hour }} jam
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-white">
                    Rp {{ number_format($rate->rate, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($rate->is_flat_rate)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100">
                            Tarif Flat
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100">
                            Per Jam
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center space-x-3">
                        <button type="button" 
                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200"
                                onclick="populateEditModal({{ $rate->id }})"
                                data-rate="{{ json_encode($rate) }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button"
                                class="delete-rate text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-200"
                                data-id="{{ $rate->id }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                    <div class="flex flex-col items-center justify-center">
                        <i class="fas fa-tags text-4xl mb-3"></i>
                        <p class="text-sm">Belum ada data tarif parkir.</p>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
