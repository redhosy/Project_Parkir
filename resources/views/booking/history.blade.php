@extends('layouts.app')

@section('title', 'Riwayat Booking Anda')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10 lg:col-md-8">
        <div class="card card-hover rounded-xl shadow-xl">
            <div class="card-header modal-header-modern text-center">
                <h4 class="mb-0 text-white"><i class="fas fa-history me-2"></i> Riwayat Booking Anda</h4>
            </div>

            <div class="card-body p-6">
                <form id="historySearchForm" class="mb-6">
                    <div class="input-group mb-2">
                        <input type="text" id="searchInput" class="form-control-modern rounded-l-lg" placeholder="Masukkan Nomor HP atau Kode Booking Anda" required>
                        <button type="submit" class="btn-modern rounded-r-lg px-4 py-2">
                            <i class="fas fa-search me-1"></i> Cari
                        </button>
                    </div>
                </form>

                <div id="booking-history-results" class="space-y-4">
                    <p class="text-center text-gray-500">Masukkan Nomor HP atau Kode Booking untuk mencari riwayat Anda.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const API_BASE_URL = '{{ url("api") }}';

    document.addEventListener('DOMContentLoaded', () => {
        const historySearchForm = document.getElementById('historySearchForm');
        const searchInput = document.getElementById('searchInput');
        const resultsContainer = document.getElementById('booking-history-results');

        historySearchForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            const query = searchInput.value.trim();

            if (!query) {
                showNotification('Mohon masukkan Nomor HP atau Kode Booking.', 'warning');
                return;
            }

            resultsContainer.innerHTML = '<p class="text-center text-gray-500">Mencari...</p>';

            try {
                const response = await fetch(`${API_BASE_URL}/booking-history?query=${encodeURIComponent(query)}`, {
                    headers: {
                        'Accept': 'application/json',
                    }
                });
                const data = await response.json();

                if (response.ok) {
                    if (data.length > 0) {
                        renderBookingHistory(data);
                        showNotification('Riwayat booking ditemukan.', 'success');
                    } else {
                        resultsContainer.innerHTML = '<p class="text-center text-gray-500">Tidak ada riwayat booking ditemukan untuk pencarian ini.</p>';
                        showNotification('Tidak ada riwayat booking ditemukan.', 'info');
                    }
                } else {
                    showNotification(data.message || 'Gagal mencari riwayat booking.', 'error');
                    resultsContainer.innerHTML = '<p class="text-center text-red-500">Terjadi kesalahan saat mencari riwayat booking.</p>';
                }
            } catch (error) {
                console.error('Error fetching booking history:', error);
                showNotification('Terjadi kesalahan jaringan atau server saat mencari riwayat.', 'error');
                resultsContainer.innerHTML = '<p class="text-center text-red-500">Terjadi kesalahan jaringan atau server.</p>';
            }
        });

        function renderBookingHistory(bookings) {
            resultsContainer.innerHTML = '';
            bookings.forEach(booking => {
                // This part needs to be carefully handled as Blade components cannot be directly
                // rendered by JavaScript in the browser. You would typically send the full HTML
                // for the badge from the API or recreate the badge HTML here.
                // For simplicity in this JS, I'll just use basic span for status.
                let statusColorClass = '';
                let statusText = '';
                switch(booking.status) {
                    case 'pending': statusColorClass = 'bg-blue-500'; statusText = 'Menunggu Check-in'; break;
                    case 'active': statusColorClass = 'bg-purple-500'; statusText = 'Sedang Parkir'; break;
                    case 'completed': statusColorClass = 'bg-green-600'; statusText = 'Selesai'; break;
                    case 'cancelled': statusColorClass = 'bg-red-600'; statusText = 'Dibatalkan'; break;
                    default: statusColorClass = 'bg-gray-500'; statusText = booking.status; break;
                }
                const statusBadgeHtml = `<span class="badge ${statusColorClass} text-white px-3 py-2 rounded-full text-xs font-semibold">${statusText}</span>`;


                const bookingCard = document.createElement('div');
                bookingCard.className = `bg-white p-5 rounded-lg shadow-md border border-gray-200`;
                bookingCard.innerHTML = `
                    <div class="flex justify-between items-center mb-3 border-b pb-2">
                        <h5 class="text-lg font-bold text-gray-800">Booking ID: <span class="text-blue-600">#${booking.id.toString().padStart(6, '0')}</span></h5>
                        ${statusBadgeHtml}
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-2 text-gray-700 text-sm">
                        <div><span class="font-semibold">Nama:</span> ${booking.nama_pemesan}</div>
                        <div><span class="font-semibold">HP:</span> ${booking.no_hp}</div>
                        <div><span class="font-semibold">Kendaraan:</span> ${booking.merk_kendaraan} (${booking.warna_kendaraan})</div>
                        <div><span class="font-semibold">Plat:</span> ${booking.license_plate}</div>
                        <div><span class="font-semibold">Slot:</span> <span class="badge bg-primary">${booking.parking_slot.code}</span></div>
                        <div><span class="font-semibold">Jenis:</span> ${booking.jenis_kendaraan.charAt(0).toUpperCase() + booking.jenis_kendaraan.slice(1)}</div>
                        <div><span class="font-semibold">Booking Time:</span> ${new Date(booking.created_at).toLocaleString('id-ID', { dateStyle: 'short', timeStyle: 'short' })}</div>
                        <div><span class="font-semibold">Masuk:</span> ${booking.actual_entry_time ? new Date(booking.actual_entry_time).toLocaleString('id-ID', { dateStyle: 'short', timeStyle: 'short' }) : '-'}</div>
                        <div><span class="font-semibold">Keluar:</span> ${booking.actual_exit_time ? new Date(booking.actual_exit_time).toLocaleString('id-ID', { dateStyle: 'short', timeStyle: 'short' }) : '-'}</div>
                        <div><span class="font-semibold">Biaya:</span> Rp ${new Intl.NumberFormat('id-ID').format(booking.total_cost || 0)}</div>
                    </div>
                    <div class="mt-4 text-center">
                        <a href="{{ url('booking') }}/${booking.id}/show" class="btn btn-sm btn-outline-primary">Lihat Detail & QR</a>
                    </div>
                `;
                resultsContainer.appendChild(bookingCard);
            });
        }
    });
</script>
@endpush
@endsection
