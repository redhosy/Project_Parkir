@extends('layouts.app')

@section('title', 'Form Booking Parkir')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10 lg:col-md-8">
        <div class="card card-hover rounded-xl shadow-xl">
            <div class="card-header modal-header-modern">
                <h4 class="mb-0 text-white"><i class="fas fa-car me-2"></i> Form Booking Parkir</h4>
            </div>

            <div class="card-body p-6">
                <form id="bookingForm" action="{{ url('api/bookings') }}" method="POST">
                    @csrf

                    <div class="mb-6 p-4 border border-gray-200 rounded-lg shadow-sm bg-gray-50">
                        <h5 class="mb-4 text-xl font-semibold border-b pb-2 text-gray-700"><i class="fas fa-user me-2"></i> Data Pemesan</h5>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="nama_pemesan" class="form-label text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" class="form-control-modern" id="nama_pemesan" name="nama_pemesan" placeholder="Nama Lengkap Anda" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="no_hp" class="form-label text-gray-700">Nomor HP (untuk Telegram) <span class="text-red-500">*</span></label>
                                <input type="tel" class="form-control-modern" id="no_hp" name="no_hp" placeholder="Contoh: 081234567890" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="email" class="form-label text-gray-700">Email (Opsional)</label>
                            <input type="email" class="form-control-modern" id="email" name="email" placeholder="Alamat Email Anda">
                        </div>
                    </div>

                    <div class="mb-6 p-4 border border-gray-200 rounded-lg shadow-sm bg-gray-50">
                        <h5 class="mb-4 text-xl font-semibold border-b pb-2 text-gray-700"><i class="fas fa-car me-2"></i> Data Kendaraan</h5>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="merk_kendaraan" class="form-label text-gray-700">Merk Kendaraan <span class="text-red-500">*</span></label>
                                <input type="text" class="form-control-modern" id="merk_kendaraan" name="merk_kendaraan" placeholder="Contoh: Toyota" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="warna_kendaraan" class="form-label text-gray-700">Warna <span class="text-red-500">*</span></label>
                                <input type="text" class="form-control-modern" id="warna_kendaraan" name="warna_kendaraan" placeholder="Contoh: Hitam" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="license_plate" class="form-label text-gray-700">Nomor Plat <span class="text-red-500">*</span></label>
                                <input type="text" class="form-control-modern" id="license_plate" name="license_plate" placeholder="Contoh: B 1234 ABC" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="jenis_kendaraan" class="form-label text-gray-700">Jenis Kendaraan <span class="text-red-500">*</span></label>
                                <select class="form-select form-control-modern" id="jenis_kendaraan" name="jenis_kendaraan" required>
                                    <option value="">Pilih Jenis</option>
                                    <option value="motor">Motor</option>
                                    <option value="mobil">Mobil</option>
                                    <option value="truk">Truk</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6 p-4 border border-gray-200 rounded-lg shadow-sm bg-gray-50">
                        <h5 class="mb-4 text-xl font-semibold border-b pb-2 text-gray-700"><i class="fas fa-parking me-2"></i> Pilih Slot Parkir <span class="text-red-500">*</span></h5>
                        <div id="parking-slots-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <p class="text-center text-gray-500 col-span-full">Memuat slot parkir...</p>
                        </div>
                        <div id="no-slots-message" class="text-center text-gray-500 mt-4 hidden">Tidak ada slot tersedia untuk jenis kendaraan ini.</div>
                    </div>

                    <div class="d-grid gap-3 mt-8">
                        <button type="submit" class="btn-modern text-lg">
                            <i class="fas fa-calendar-check me-2"></i> Booking Sekarang
                        </button>
                        <a href="/" class="btn-outline-modern text-lg text-center">
                            <i class="fas fa-times me-2"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const API_BASE_URL = '{{ url("api") }}';
    let availableSlots = [];

    function fetchAvailableSlots() {
        fetch(`${API_BASE_URL}/parking-slots`)
            .then(response => response.json())
            .then(data => {
                availableSlots = data.filter(slot => slot.status === 'available');
                renderParkingSlots();
            })
            .catch(error => {
                console.error('Error fetching parking slots:', error);
                showNotification('Gagal memuat slot parkir.', 'error');
            });
    }

    function renderParkingSlots(filterType = '') {
        const container = document.getElementById('parking-slots-container');
        const noSlotsMessage = document.getElementById('no-slots-message');
        container.innerHTML = '';

        let filteredSlots = availableSlots;
        if (filterType && filterType !== '') {
            filteredSlots = availableSlots.filter(slot => slot.type === filterType);
        }

        if (filteredSlots.length === 0) {
            noSlotsMessage.classList.remove('hidden');
            return;
        } else {
            noSlotsMessage.classList.add('hidden');
        }

        filteredSlots.forEach(slot => {
            const slotCard = document.createElement('div');
            slotCard.className = `card card-hover rounded-lg shadow-md p-4 bg-white text-gray-800 cursor-pointer transition duration-300`;
            slotCard.innerHTML = `
                <div class="form-check flex items-center justify-between">
                    <input class="form-check-input hidden peer" type="radio" name="parking_slot_id"
                           id="slot_${slot.id}" value="${slot.id}" required>
                    <label class="form-check-label w-full p-2 rounded-lg cursor-pointer flex justify-between items-center border border-gray-300 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:shadow-inner transition duration-200" for="slot_${slot.id}">
                        <div>
                            <strong class="text-lg text-gray-800">Slot ${slot.code}</strong>
                            <div class="text-sm text-gray-500">${slot.location_description || 'Tanpa Deskripsi'}</div>
                        </div>
                        <div class="text-right">
                            <span class="badge bg-primary text-white px-3 py-1 rounded-full text-xs">${slot.type.charAt(0).toUpperCase() + slot.type.slice(1)}</span>
                            <div class="mt-1 text-sm font-semibold text-gray-700">Rp ${new Intl.NumberFormat('id-ID').format(slot.tarif)}/jam</div>
                        </div>
                    </label>
                </div>
            `;
            container.appendChild(slotCard);
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        fetchAvailableSlots();

        const jenisKendaraanSelect = document.getElementById('jenis_kendaraan');
        jenisKendaraanSelect.addEventListener('change', (event) => {
            renderParkingSlots(event.target.value);
        });

        const bookingForm = document.getElementById('bookingForm');
        bookingForm.addEventListener('submit', async (event) => {
            event.preventDefault();

            const formData = new FormData(bookingForm);
            const data = Object.fromEntries(formData.entries());

            data.user_id = 1;
            data.vehicle_id = 1;

            const now = new Date();
            const intendedEntry = new Date(now.getTime() + 5 * 60 * 1000);
            const intendedExit = new Date(intendedEntry.getTime() + 60 * 60 * 1000);

            data.intended_entry_time = intendedEntry.toISOString();
            data.intended_exit_time = intendedExit.toISOString();

            try {
                const response = await fetch(bookingForm.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    showNotification(result.message, 'success');
                    window.location.href = `{{ url('booking') }}/${result.booking.id}/show`;
                } else {
                    let errorMessage = result.message || 'Terjadi kesalahan saat booking.';
                    if (result.errors) {
                        errorMessage += '\n' + Object.values(result.errors).flat().join('\n');
                    }
                    showNotification(errorMessage, 'error');
                }
            } catch (error) {
                console.error('Error submitting booking:', error);
                showNotification('Terjadi kesalahan jaringan atau server.', 'error');
            }
        });
    });
</script>
@endpush
@endsection