@extends('layouts.app')

@section('title', 'Manajemen Slot Parkir')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-gradient fw-bold"><i class="fas fa-parking me-2"></i> Manajemen Slot Parkir</h2>
        <button class="btn-modern" data-bs-toggle="modal" data-bs-target="#addSlotModal">
            <i class="fas fa-plus me-2"></i> Tambah Slot
        </button>
    </div>

    <div class="card mb-4 rounded-xl shadow-md">
        <div class="card-body p-5">
            <form id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="filterStatus" class="form-label text-gray-700">Status</label>
                        <select id="filterStatus" name="status" class="form-select form-control-modern">
                            <option value="">Semua</option>
                            <option value="available">Tersedia</option>
                            <option value="booked">Dibooking</option>
                            <option value="occupied">Terisi</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filterType" class="form-label text-gray-700">Jenis Kendaraan</label>
                        <select id="filterType" name="type" class="form-select form-control-modern">
                            <option value="">Semua</option>
                            <option value="motor">Motor</option>
                            <option value="mobil">Mobil</option>
                            <option value="truk">Truk</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filterArea" class="form-label text-gray-700">Zona</label>
                        <select id="filterArea" name="area" class="form-select form-control-modern">
                            <option value="">Semua</option>
                            <option value="A">Zona A</option>
                            <option value="B">Zona B</option>
                            <option value="C">Zona C</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn-outline-modern w-100">
                            <i class="fas fa-filter me-2"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card rounded-xl shadow-xl">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">Kode Slot</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">Lokasi</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">Jenis</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">Tarif/Jam</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">Zona</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">Status</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($slots as $slot)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition duration-200">
                            <td class="py-3 px-4"><strong>{{ $slot->code }}</strong></td>
                            <td class="py-3 px-4">{{ $slot->location_description ?? '-' }}</td>
                            <td class="py-3 px-4">{{ ucfirst($slot->type) }}</td>
                            <td class="py-3 px-4">Rp {{ number_format($slot->tarif, 0) }}</td>
                            <td class="py-3 px-4">{{ $slot->area }}</td>
                            <td class="py-3 px-4">
                                @include('components.status-badge', ['status' => $slot->status])
                            </td>
                            <td class="py-3 px-4">
                                <button class="btn btn-sm btn-outline-primary me-2"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editSlotModal"
                                        data-id="{{ $slot->id }}"
                                        data-code="{{ $slot->code }}"
                                        data-type="{{ $slot->type }}"
                                        data-tarif="{{ $slot->tarif }}"
                                        data-status="{{ $slot->status }}"
                                        data-area="{{ $slot->area }}"
                                        data-location="{{ $slot->location_description }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-slot" data-id="{{ $slot->id }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-4 text-center text-gray-500">Tidak ada slot parkir ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4 p-4">
                {{ $slots->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

@include('admin.parking_slots._add_modal')
@include('admin.parking_slots._edit_modal')

@push('scripts')
<script>
    const API_BASE_URL = '{{ url("api") }}';

    document.addEventListener('DOMContentLoaded', function() {
        var editModal = document.getElementById('editSlotModal');
        editModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var code = button.getAttribute('data-code');
            var type = button.getAttribute('data-type');
            var tarif = button.getAttribute('data-tarif');
            var area = button.getAttribute('data-area');
            var status = button.getAttribute('data-status');
            var location = button.getAttribute('data-location');

            var form = editModal.querySelector('#editSlotForm');
            form.action = `${API_BASE_URL}/admin/parking-slots/${id}`;

            editModal.querySelector('#editId').value = id;
            editModal.querySelector('#editCode').value = code;
            editModal.querySelector('#editTarif').value = tarif;
            editModal.querySelector('#editLocation').value = location;

            editModal.querySelector('#editType').value = type;
            editModal.querySelector('#editArea').value = area;
            editModal.querySelector('#editStatus').value = status;
        });

        const addSlotForm = document.getElementById('addSlotForm');
        addSlotForm.addEventListener('submit', async function(event) {
            event.preventDefault();
            const formData = new FormData(addSlotForm);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch(`${API_BASE_URL}/admin/parking-slots/add`, {
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
                    var addModal = bootstrap.Modal.getInstance(document.getElementById('addSlotModal'));
                    addModal.hide();
                    location.reload();
                } else {
                    let errorMessage = result.message || 'Gagal menambahkan slot.';
                    if (result.errors) {
                        errorMessage += '\n' + Object.values(result.errors).flat().join('\n');
                    }
                    showNotification(errorMessage, 'error');
                }
            } catch (error) {
                console.error('Error adding slot:', error);
                showNotification('Terjadi kesalahan jaringan atau server.', 'error');
            }
        });

        const editSlotForm = document.getElementById('editSlotForm');
        editSlotForm.addEventListener('submit', async function(event) {
            event.preventDefault();
            const formData = new FormData(editSlotForm);
            const data = Object.fromEntries(formData.entries());
            const slotId = document.getElementById('editId').value;

            try {
                const response = await fetch(`${API_BASE_URL}/admin/parking-slots/${slotId}`, {
                    method: 'PUT',
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
                    var editModalInstance = bootstrap.Modal.getInstance(document.getElementById('editSlotModal'));
                    editModalInstance.hide();
                    location.reload();
                } else {
                    let errorMessage = result.message || 'Gagal mengupdate slot.';
                    if (result.errors) {
                        errorMessage += '\n' + Object.values(result.errors).flat().join('\n');
                    }
                    showNotification(errorMessage, 'error');
                }
            } catch (error) {
                console.error('Error updating slot:', error);
                showNotification('Terjadi kesalahan jaringan atau server.', 'error');
            }
        });

        document.querySelectorAll('.delete-slot').forEach(button => {
            button.addEventListener('click', async function() {
                if (confirm('Apakah Anda yakin ingin menghapus slot ini?')) {
                    var id = this.getAttribute('data-id');
                    try {
                        const response = await fetch(`${API_BASE_URL}/admin/parking-slots/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        });
                        const result = await response.json();
                        if (response.ok) {
                            showNotification(result.message, 'success');
                            location.reload();
                        } else {
                            showNotification(result.message || 'Gagal menghapus slot.', 'error');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showNotification('Terjadi kesalahan jaringan.', 'error');
                    }
                }
            });
        });
    });
</script>
@endpush
@endsection