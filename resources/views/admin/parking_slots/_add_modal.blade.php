<!-- Add Parking Slot Modal -->
<div id="addParkingSlotModal" tabindex="-1" aria-hidden="true" 
    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-2xl shadow-xl dark:bg-gray-800 transform transition-all">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-5 border-b rounded-t-2xl bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Tambah Slot Parkir
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="addParkingSlotModal">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-6 space-y-6">
                <form id="addParkingSlotForm" action="{{ route('admin.parking_slots.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <!-- Code -->
                    <div>
                        <label for="code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode Slot <span class="text-red-500">*</span></label>
                        <input type="text" id="code" name="code" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required maxlength="10" placeholder="Contoh: A01">
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis Kendaraan <span class="text-red-500">*</span></label>
                        <select id="type" name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            <option value="motor">Motor</option>
                            <option value="mobil">Mobil</option>
                            <option value="truk">Truk</option>
                        </select>
                    </div>

                    <!-- Parking Rate -->
                    <div>
                        <label for="parking_rate_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tarif Parkir <span class="text-red-500">*</span></label>
                        <select id="parking_rate_id" name="parking_rate_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            <option value="">Pilih Tarif Parkir</option>
                            @foreach($groupedRates as $vehicleType => $rates)
                                <optgroup label="{{ $vehicleType === 'motor' ? 'Motor' : ($vehicleType === 'mobil' ? 'Mobil' : 'Truk') }}">
                                    @foreach($rates as $rate)
                                        <option value="{{ $rate->id }}" data-vehicle-type="{{ $rate->vehicle_type }}">
                                            @if($rate->duration_end_hour)
                                                {{ $rate->duration_start_hour }} - {{ $rate->duration_end_hour }} jam : Rp {{ number_format($rate->rate, 0, ',', '.') }}
                                            @elseif($rate->is_flat_rate)
                                                ≥ {{ $rate->duration_start_hour }} jam (Tarif flat) : Rp {{ number_format($rate->rate, 0, ',', '.') }}
                                            @else
                                                ≥ {{ $rate->duration_start_hour }} jam : Rp {{ number_format($rate->rate, 0, ',', '.') }}
                                            @endif
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <!-- Area -->
                    <div>
                        <label for="area" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Area <span class="text-red-500">*</span></label>
                        <select id="area" name="area" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            <option value="A">Area A</option>
                            <option value="B">Area B</option>
                            <option value="C">Area C</option>
                        </select>
                    </div>

                    <!-- Location Description -->
                    <div>
                        <label for="location_description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi Lokasi</label>
                        <textarea id="location_description" name="location_description" rows="2" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Deskripsi opsional tentang lokasi slot"></textarea>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status <span class="text-red-500">*</span></label>
                        <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            <option value="available">Tersedia</option>
                            <option value="maintenance">Pemeliharaan</option>
                        </select>
                    </div>
                </form>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center justify-end p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-700">
                <button type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600" data-modal-hide="addParkingSlotModal">
                    Batal
                </button>
                <button type="submit" form="addParkingSlotForm" class="text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:focus:ring-blue-800 transition-all duration-200 ease-in-out transform hover:scale-105">
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for form submission -->
<script>
// Filter parking rates based on vehicle type
document.getElementById('type').addEventListener('change', function() {
    const selectedVehicleType = this.value;
    const parkingRateSelect = document.getElementById('parking_rate_id');
    const options = parkingRateSelect.querySelectorAll('option');
    const optgroups = parkingRateSelect.querySelectorAll('optgroup');
    
    // Hide all optgroups first
    optgroups.forEach(optgroup => {
        optgroup.style.display = 'none';
    });
    
    // Show only the optgroup that matches the selected vehicle type
    const selectedOptgroup = parkingRateSelect.querySelector(`optgroup[label="${selectedVehicleType === 'motor' ? 'Motor' : (selectedVehicleType === 'mobil' ? 'Mobil' : 'Truk')}"]`);
    if (selectedOptgroup) {
        selectedOptgroup.style.display = 'block';
        // Select the first option in the group if available
        const firstOption = selectedOptgroup.querySelector('option');
        if (firstOption) {
            firstOption.selected = true;
        }
    }
});

// Trigger the change event on page load to set initial state
// We're already in a script that runs after DOM is loaded, so we can call this directly
document.getElementById('type').dispatchEvent(new Event('change'));

document.getElementById('addParkingSlotForm').addEventListener('submit', function(e) {
    e.preventDefault();

    fetch(this.action, {
        method: 'POST',
        body: new FormData(this),
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            // Show success message
            alert(data.message);
            
            // Hide modal
            document.querySelector('[data-modal-hide="addParkingSlotModal"]').click();
            
            // Refresh the page or update the table
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (error.errors) {
            // Show validation errors
            Object.keys(error.errors).forEach(field => {
                const input = document.querySelector(`[name="${field}"]`);
                if (input) {
                    input.classList.add('border-red-500');
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'text-red-500 text-xs mt-1';
                    errorDiv.textContent = error.errors[field][0];
                    input.parentNode.appendChild(errorDiv);
                }
            });
        }
    });
});