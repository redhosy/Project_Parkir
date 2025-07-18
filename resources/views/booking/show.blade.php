@extends('layouts.app')

@section('title', 'Detail Booking Parkir')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 lg:col-md-6">
        <div class="card card-hover rounded-xl shadow-xl">
            <div class="card-header modal-header-modern text-center">
                <h4 class="mb-0 text-white"><i class="fas fa-receipt me-2"></i> Detail Booking Parkir</h4>
            </div>

            <div class="card-body p-6 text-center">
                <div class="mb-6 p-4 border border-gray-300 rounded-lg bg-gray-50 shadow-inner">
                    <h5 class="mb-3 text-xl font-semibold text-gray-700">Scan QR Code Ini di Gerbang Parkir</h5>
                    <div id="qrcode-display" class="bg-white p-2 rounded-lg inline-block shadow-md">
                    </div>
                    <p class="mt-3 text-lg font-mono text-blue-600 break-words">
                        Booking ID: <span id="display-booking-code" class="font-bold"></span>
                    </p>
                    <div class="mt-2 text-sm text-gray-500">Tunjukkan kode ini kepada penjaga portal.</div>
                </div>

                <div class="text-start mb-6 p-4 border border-gray-200 rounded-lg shadow-sm bg-white">
                    <h5 class="mb-4 text-xl font-semibold border-b pb-2 text-gray-700"><i class="fas fa-info-circle me-2"></i> Detail Booking</h5>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-3 gap-x-6 text-gray-700">
                        <div><span class="font-semibold">Nama Pemesan:</span> {{ $booking->nama_pemesan }}</div>
                        <div><span class="font-semibold">Nomor HP:</span> {{ $booking->no_hp }}</div>
                        <div><span class="font-semibold">Email:</span> {{ $booking->email ?? '-' }}</div>
                        <div><span class="font-semibold">Kendaraan:</span> {{ $booking->merk_kendaraan }} ({{ $booking->warna_kendaraan }})</div>
                        <div><span class="font-semibold">Nomor Plat:</span> {{ $booking->license_plate }}</div>
                        <div><span class="font-semibold">Jenis Kendaraan:</span> {{ ucfirst($booking->jenis_kendaraan) }}</div>
                        <div><span class="font-semibold">Slot Parkir:</span> <span class="badge bg-primary">{{ $booking->parkingSlot->code }}</span></div>
                        <div><span class="font-semibold">Lokasi Slot:</span> {{ $booking->parkingSlot->location_description ?? '-' }}</div>
                        <div><span class="font-semibold">Waktu Booking:</span> {{ \Carbon\Carbon::parse($booking->created_at)->format('d/m/Y H:i') }}</div>
                        {{-- Tambahkan waktu masuk/keluar aktual jika ingin ditampilkan --}}
                        @if($booking->entry_time)
                        <div><span class="font-semibold">Waktu Masuk:</span> {{ \Carbon\Carbon::parse($booking->entry_time)->format('d/m/Y H:i') }}</div>
                        @endif
                        @if($booking->exit_time)
                        <div><span class="font-semibold">Waktu Keluar:</span> {{ \Carbon\Carbon::parse($booking->exit_time)->format('d/m/Y H:i') }}</div>
                        @endif
                        <div><span class="font-semibold">Status Booking:</span>
                            @include('components.status-badge', ['status' => $booking->status])
                        </div>
                    </div>
                </div>

                <div class="alert alert-info p-4 rounded-lg shadow-md bg-blue-50 text-blue-800 border border-blue-200">
                    <h5 class="mb-3 text-lg font-semibold"><i class="fas fa-info-circle me-2"></i> Instruksi Parkir</h5>
                    <ol class="text-start list-decimal list-inside space-y-1">
                        <li>Tunjukkan QR Code ini di gerbang masuk kepada penjaga.</li>
                        <li>Setelah validasi, palang akan terbuka otomatis.</li>
                        <li>Parkir di slot yang telah Anda pilih.</li>
                        <li>Untuk keluar, tunjukkan lagi QR Code yang sama kepada penjaga untuk pembayaran dan validasi keluar.</li>
                    </ol>
                </div>

                <a href="/" class="btn-modern mt-6 w-full sm:w-auto">
                    <i class="fas fa-home me-2"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const bookingCode = "{{ $booking->qr_code }}";
        const bookingId = "{{ $booking->id }}";
        document.getElementById('display-booking-code').textContent = bookingCode;
        window.generateQrCode('qrcode-display', bookingCode);
    });
</script>
@endpush
@endsection
