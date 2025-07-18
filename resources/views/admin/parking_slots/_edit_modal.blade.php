<!-- Modal Edit -->
<div class="modal fade" id="editSlotModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Slot Parkir</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Slot</label>
                        <input type="text" class="form-control" name="code" id="editCode" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipe</label>
                        <select class="form-select" name="type" id="editType" required>
                            <option value="motor">Motor</option>
                            <option value="mobil">Mobil</option>
                            <option value="truk">Truk</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tarif</label>
                        <input type="number" class="form-control" name="tarif" id="editTarif" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status" id="editStatus" required>
                            <option value="available">Available</option>
                            <option value="booked">Booked</option>
                            <option value="occupied">Occupied</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editModal = document.getElementById('editSlotModal');
    
    editModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const slotId = button.getAttribute('data-id');
        const slotCode = button.getAttribute('data-code');
        const slotType = button.getAttribute('data-type');
        const slotTarif = button.getAttribute('data-tarif');
        const slotStatus = button.getAttribute('data-status');
        
        const form = document.getElementById('editForm');
        form.action = `/parking/${slotId}`;
        
        document.getElementById('editCode').value = slotCode;
        document.getElementById('editType').value = slotType;
        document.getElementById('editTarif').value = slotTarif;
        document.getElementById('editStatus').value = slotStatus;
    });
});
</script>