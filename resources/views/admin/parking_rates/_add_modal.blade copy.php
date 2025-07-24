<!-- Add Rate Modal -->
<div id="addRateModal" tabindex="-1" aria-hidden="true" 
    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-2xl shadow-xl dark:bg-gray-800 transform transition-all">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-5 border-b rounded-t-2xl bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Tambah Tarif Parkir
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" onclick="window.addRateModal.hide()">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-6 space-y-6">
                <form id="addRateForm" action="{{ route('admin.parking_rates.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <!-- Vehicle Type -->
                    <div>
                        <label for="vehicle_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis Kendaraan <span class="text-red-500">*</span></label>
                        <select id="vehicle_type" name="vehicle_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            <option value="motor">Motor</option>
                            <option value="mobil">Mobil</option>
                            <option value="truk">Truk</option>
                        </select>
                    </div>

                    <!-- Start Hour -->
                    <div>
                        <label for="duration_start_hour" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mulai Jam Ke- <span class="text-red-500">*</span></label>
                        <input type="number" id="duration_start_hour" name="duration_start_hour" min="0" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                    </div>

                    <!-- End Hour -->
                    <div>
                        <label for="duration_end_hour" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sampai Jam Ke-</label>
                        <input type="number" id="duration_end_hour" name="duration_end_hour" min="0" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Kosongkan untuk durasi tak terbatas">
                    </div>

                    <!-- Rate -->
                    <div>
                        <label for="rate" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tarif (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" id="rate" name="rate" min="0" step="500" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                    </div>

                    <!-- Is Flat Rate -->
                    <div class="flex items-center">
                        <input type="checkbox" id="is_flat_rate" name="is_flat_rate" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="is_flat_rate" class="ml-2 text-sm font-medium text-gray-900 dark:text-white">
                            Tarif Tetap (bukan per jam)
                        </label>
                    </div>
                </form>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center justify-end p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-700">
                <button type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600" onclick="window.addRateModal.hide()">
                    Batal
                </button>
                <button type="submit" form="addRateForm" class="text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:focus:ring-blue-800 transition-all duration-200 ease-in-out transform hover:scale-105">
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addModalEl = document.getElementById('addRateModal');
    const addModal = new Modal(addModalEl, {
        placement: 'center',
        backdrop: 'dynamic',
        backdropClasses: 'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40',
        closable: true
    });
    window.addRateModal = addModal;

    // Handle form submission
    document.getElementById('addRateForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
        
        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            const data = await response.json();
            
            if (response.ok) {
                addModal.hide();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.reload();
                });
            } else {
                if (data.errors) {
                    // Clear previous error messages
                    const errorDivs = this.querySelectorAll('.error-message');
                    errorDivs.forEach(div => div.remove());
                    
                    // Show error messages under each field
                    Object.keys(data.errors).forEach(field => {
                        const input = this.querySelector(`[name="${field}"]`);
                        if (input) {
                            const errorDiv = document.createElement('div');
                            errorDiv.className = 'error-message text-red-500 text-xs mt-1';
                            errorDiv.textContent = data.errors[field][0];
                            input.parentNode.appendChild(errorDiv);
                        }
                    });
                }
                throw new Error(data.message || 'Terjadi kesalahan');
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: error.message
            });
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });
});
</script>
