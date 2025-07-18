@extends('layouts.app')

@section('title', 'Manajemen Slot Parkir')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-parking me-2"></i> Manajemen Slot Parkir</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSlotModal">
            <i class="fas fa-plus me-2"></i> Tambah Slot
        </button>
    </div>
    
    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form>
                <div class="row">
                    <div class="col-md-3">
                        <label for="filterStatus" class="form-label">Status</label>
                        <select id="filterStatus" class="form-select">
                            <option value="">Semua</option>
                            <option value="available">Tersedia</option>
                            <option value="booked">Dibooking</option>
                            <option value="occupied">Terisi</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filterType" class="form-label">Jenis Kendaraan</label>
                        <select id="filterType" class="form-select">
                            <option value="">Semua</option>
                            <option value="motor">Motor</option>
                            <option value="mobil">Mobil</option>
                            <option value="truk">Truk</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filterZone" class="form-label">Zona</label>
                        <select id="filterZone" class="form-select">
                            <option value="">Semua</option>
                            <option value="A">Zona A</option>
                            <option value="B">Zona B</option>
                            <option value="C">Zona C</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-2"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Daftar Slot -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>Kode Slot</th>
                            <th>Lokasi</th>
                            <th>Jenis</th>
                            <th>Tarif/Jam</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($slots as $slot)
                        <tr>
                            <td><strong>{{ $slot->code }}</strong></td>
                            <td>{{ $slot->location_description }}</td>
                            <td>{{ ucfirst($slot->type) }}</td>
                            <td>Rp {{ number_format($slot->tarif, 0) }}</td>
                            <td>{{ $slot->area }}</td> 
                            <td>
                                @if($slot->status == 'available')
                                    <span class="badge bg-success">Tersedia</span>
                                @elseif($slot->status == 'booked')
                                    <span class="badge bg-warning">Dibooking</span>
                                @else
                                    <span class="badge bg-danger">Terisi</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" 
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
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $slots->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Slot -->
<div class="modal fade" id="addSlotModal" tabindex="-1" aria-hidden="true">
     <div class="modal-dialog">
        <div class="modal-content">
            <form id="addSlotForm" action="{{ route('parking.store') }}" method="POST">
                @csrf
                {{-- <input type="hidden" name="slot_number" value="{{ substr(md5(uniqid()), 0, 8) }}"> --}}
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="area" class="form-label">Zona Parkir</label>
                        <select class="form-select" id="area" name="area" required>
                            <option value="A">Zona A</option>
                            <option value="B">Zona B</option>
                            <option value="C">Zona C</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="code" class="form-label">Kode Slot</label>
                        <input type="text" class="form-control" id="code" name="code" required>
                    </div>
                    <div class="mb-3">
                        <label for="addType" class="form-label">Jenis Kendaraan</label>
                        <select class="form-select" id="addType" name="type" required>
                            <option value="motor">Motor</option>
                            <option value="mobil">Mobil</option>
                            <option value="truk">Truk</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="addTarif" class="form-label">Tarif per Jam</label>
                        <input type="number" class="form-control" id="addTarif" name="tarif" required>
                    </div>
                    <div class="mb-3">
                        <label for="addLocation" class="form-label">Deskripsi Lokasi</label>
                        <textarea class="form-control" id="addLocation" name="location_description" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Slot -->
@include('parking._edit_modal')
<!-- Modal Edit Slot -->

<script>
// Edit Modal Handler
document.addEventListener('DOMContentLoaded', function() {
    var editModal = document.getElementById('editSlotModal');
    editModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id'); // ID dari tombol edit
        var code = button.getAttribute('data-code');
        var type = button.getAttribute('data-type');
        var tarif = button.getAttribute('data-tarif');
        var area = button.getAttribute('data-area');
        var status = button.getAttribute('data-status');
        var location = button.getAttribute('data-location');

        var form = editModal.querySelector('#editSlotForm');
        // Set action form secara dinamis
        form.action = '{{ url("parking") }}/' + id; // Menggunakan url() helper

        // Isi input hidden untuk ID (jika diperlukan oleh backend)
        editModal.querySelector('#editId').value = id;

        editModal.querySelector('#editCode').value = code;
        editModal.querySelector('#editType').value = type;
        editModal.querySelector('#editTarif').value = tarif;
        editModal.querySelector('#editArea').value = area;
        editModal.querySelector('#editStatus').value = status;
        editModal.querySelector('#editLocation').value = location;

        // Set nilai 'selected' untuk dropdown
        editModal.querySelector('#editType').value = type;
        editModal.querySelector('#editArea').value = area;
        editModal.querySelector('#editStatus').value = status;
    });

    // Delete Handler
    document.querySelectorAll('.delete-slot').forEach(button => {
        button.addEventListener('click', function() {
            // Ganti confirm() dengan modal kustom jika tidak ingin menggunakan alert browser
            if (confirm('Apakah Anda yakin ingin menghapus slot ini?')) {
                var id = this.getAttribute('data-id');
                fetch('/parking/' + id, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                }).then(response => {
                    if (response.ok) {
                        location.reload();
                    } else {
                        // Tangani error jika terjadi
                        response.json().then(data => alert(data.message || 'Gagal menghapus slot.'));
                    }
                }).catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan jaringan.');
                });
            }
        });
    });
});
</script>
@endsection