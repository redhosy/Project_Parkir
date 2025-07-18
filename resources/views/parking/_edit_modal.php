<!-- Modal Edit Slot -->
<div class="modal fade" id="editSlotModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Slot Parkir</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSlotForm" action="" method="POST"> {{-- Hapus route() dari sini --}}
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="editId" name="id"> {{-- Tambahkan input hidden untuk ID --}}
                    <div class="mb-3">
                        <label for="editArea" class="form-label">Zona Parkir</label>
                        <select class="form-select" id="editArea" name="area" required>
                            <option value="A">Zona A</option>
                            <option value="B">Zona B</option>
                            <option value="C">Zona C</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editCode" class="form-label">Kode Slot</label>
                        <input type="text" class="form-control" id="editCode" name="code" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editType" class="form-label">Jenis Kendaraan</label>
                        <select class="form-select" id="editType" name="type" required>
                            <option value="motor">Motor</option>
                            <option value="mobil">Mobil</option>
                            <option value="truk">Truk</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editTarif" class="form-label">Tarif per Jam</label>
                        <input type="number" class="form-control" id="editTarif" name="tarif" required>
                    </div>
                    <div class="mb-3">
                        <label for="editStatus" class="form-label">Status</label>
                        <select class="form-select" id="editStatus" name="status" required>
                            <option value="available">Tersedia</option>
                            <option value="booked">Dibooking</option>
                            <option value="occupied">Terisi</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editLocation" class="form-label">Deskripsi Lokasi</label>
                        <textarea class="form-control" id="editLocation" name="location_description" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>