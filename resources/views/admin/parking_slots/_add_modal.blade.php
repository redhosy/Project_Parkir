<!-- Modal Tambah Slot -->
<div class="modal fade" id="addSlotModal" tabindex="-1" aria-hidden="true">
     <div class="modal-dialog">
        <div class="modal-content modal-content-modern">
            <div class="modal-header modal-header-modern">
                <h5 class="modal-title text-white">Tambah Slot Parkir Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addSlotForm" action="{{ route('admin.parking_slots.store') }}" method="POST">
                @csrf
                <div class="modal-body p-5">
                    <div class="mb-4">
                        <label for="area" class="form-label text-gray-700">Zona Parkir <span class="text-red-500">*</span></label>
                        <select class="form-select form-control-modern" id="area" name="area" required>
                            <option value="A">Zona A</option>
                            <option value="B">Zona B</option>
                            <option value="C">Zona C</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="code" class="form-label text-gray-700">Kode Slot <span class="text-red-500">*</span></label>
                        <input type="text" class="form-control-modern" id="code" name="code" placeholder="Contoh: A01" required>
                    </div>
                    <div class="mb-4">
                        <label for="addType" class="form-label text-gray-700">Jenis Kendaraan <span class="text-red-500">*</span></label>
                        <select class="form-select form-control-modern" id="addType" name="type" required>
                            <option value="motor">Motor</option>
                            <option value="mobil">Mobil</option>
                            <option value="truk">Truk</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="addTarif" class="form-label text-gray-700">Tarif per Jam (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" class="form-control-modern" id="addTarif" name="tarif" placeholder="Contoh: 5000" required>
                    </div>
                    <div class="mb-4">
                        <label for="addLocation" class="form-label text-gray-700">Deskripsi Lokasi (Opsional)</label>
                        <textarea class="form-control-modern" id="addLocation" name="location_description" rows="2" placeholder="Contoh: Dekat Pintu Masuk"></textarea>
                    </div>
                </div>
                <div class="modal-footer modal-footer-modern">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-modern">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>