/**
 * Global Modal Handler for SmartPark Application
 * Initializes and manages modals for both parking slots and parking rates
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('Modal handler initializing');
    
    // Make sure Flowbite is available globally
    if (typeof window.Flowbite === 'undefined' && typeof flowbite !== 'undefined') {
        console.log('Setting up global Flowbite reference');
        window.Flowbite = {
            Modal: flowbite.Modal
        };
    }
    
    // Function to clean up modal backdrops to prevent duplicates
    window.cleanupModalBackdrops = function() {
        document.querySelectorAll('[modal-backdrop]').forEach(backdrop => {
            backdrop.remove();
        });
    };
    
    // Function to initialize modal with proper options
    window.initializeModal = function(modalId, options = {}) {
        const modalEl = document.getElementById(modalId);
        if (!modalEl) {
            console.error(`Modal element with ID ${modalId} not found`);
            return null;
        }
        
        // Default options with backdrop handling
        const defaultOptions = {
            backdropClasses: 'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40',
            onShow: () => {
                document.body.style.overflow = 'hidden';
                window.cleanupModalBackdrops();
            },
            onHide: () => {
                window.cleanupModalBackdrops();
                document.body.style.overflow = '';
            }
        };
        
        // Merge default options with provided options
        const mergedOptions = { ...defaultOptions, ...options };
        
        try {
            if (window.Flowbite && window.Flowbite.Modal) {
                const modal = new window.Flowbite.Modal(modalEl, mergedOptions);
                return modal;
            } else if (typeof flowbite !== 'undefined') {
                const modal = new flowbite.Modal(modalEl, mergedOptions);
                return modal;
            } else {
                throw new Error('Flowbite library not available');
            }
        } catch (error) {
            console.error(`Error initializing modal ${modalId}:`, error);
            return null;
        }
    };

    // Automatically initialize modals if they exist
    // Parking Slots modals
    if (document.getElementById('addParkingSlotModal')) {
        window.addParkingSlotModal = window.initializeModal('addParkingSlotModal');
    }
    
    if (document.getElementById('editParkingSlotModal')) {
        window.editParkingSlotModal = window.initializeModal('editParkingSlotModal');
    }
    
    // Parking Rates modals
    if (document.getElementById('addRateModal')) {
        window.addRateModal = window.initializeModal('addRateModal');
    }
    
    if (document.getElementById('editRateModal')) {
        window.editRateModal = window.initializeModal('editRateModal');
    }

    // Add event listeners to toggle buttons
    document.querySelectorAll('[data-modal-toggle]').forEach(button => {
        button.addEventListener('click', () => {
            const targetId = button.getAttribute('data-modal-toggle');
            const modalVar = `${targetId.charAt(0).toLowerCase() + targetId.slice(1)}`;
            
            if (window[modalVar]) {
                window[modalVar].show();
            } else {
                // Try to initialize on demand
                window[modalVar] = window.initializeModal(targetId);
                if (window[modalVar]) {
                    window[modalVar].show();
                } else {
                    console.error(`Failed to initialize modal ${targetId}`);
                }
            }
        });
    });
    
    // Add event listeners for modal hide buttons
    document.querySelectorAll('[data-modal-hide]').forEach(button => {
        button.addEventListener('click', () => {
            const targetId = button.getAttribute('data-modal-hide');
            const modalVar = `${targetId.charAt(0).toLowerCase() + targetId.slice(1)}`;
            
            if (window[modalVar]) {
                window[modalVar].hide();
            }
        });
    });

    // Handle openEditModal for Parking Slots
    window.openEditModal = function(data) {
        try {
            console.log('Opening edit modal for parking slot:', data);
            
            // Fill form data
            document.getElementById('edit-slot-code').textContent = `(${data.code})`;
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_code').value = data.code;
            document.getElementById('edit_type').value = data.type;
            document.getElementById('edit_parking_rate_id').value = data.parking_rate_id || '';
            document.getElementById('edit_area').value = data.area;
            document.getElementById('edit_location_description').value = data.location_description || '';
            document.getElementById('edit_status').value = data.status;
            
            // Trigger the change event if needed
            if (document.getElementById('edit_type').dispatchEvent) {
                document.getElementById('edit_type').dispatchEvent(new Event('change'));
            }

            // Update form action
            document.getElementById('editParkingSlotForm').setAttribute('action', `/admin/parking-slots/${data.id}`);

            // Clean up any existing backdrops
            window.cleanupModalBackdrops();

            // Show the modal
            if (window.editParkingSlotModal) {
                window.editParkingSlotModal.show();
            } else {
                // If the global variable isn't set, try to initialize the modal
                window.editParkingSlotModal = window.initializeModal('editParkingSlotModal');
                if (window.editParkingSlotModal) {
                    window.editParkingSlotModal.show();
                } else {
                    console.error('Edit parking slot modal not properly initialized');
                    alert('Terjadi kesalahan saat membuka modal edit. Silakan refresh halaman dan coba lagi.');
                }
            }
        } catch (error) {
            console.error('Error opening edit modal for parking slot:', error);
            alert('Terjadi kesalahan saat membuka modal edit. Silakan coba lagi.');
        }
    };

    // Handle populateEditModal for Parking Rates
    window.populateEditModal = function(rateId) {
        try {
            console.log('Populating edit modal for rate ID:', rateId);
            
            // Find the button with data-rate attribute
            const button = document.querySelector(`button[data-rate][onclick*="populateEditModal(${rateId})"]`);
            if (!button) {
                throw new Error('Button not found for rate ID: ' + rateId);
            }
            
            const data = JSON.parse(button.getAttribute('data-rate'));
            if (!data) {
                throw new Error('No data found in data-rate attribute');
            }
            
            console.log('Rate data loaded successfully:', data);
            
            // Set form action with correct rate ID
            const form = document.getElementById('editRateForm');
            form.action = `/admin/parking-rates/${data.id}`;
            
            // Set values to form fields
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_vehicle_type').value = data.vehicle_type;
            document.getElementById('edit_duration_start_hour').value = data.duration_start_hour;
            document.getElementById('edit_duration_end_hour').value = data.duration_end_hour || '';
            document.getElementById('edit_rate').value = data.rate;
            
            // Set the correct radio button
            if (data.is_flat_rate) {
                document.getElementById('edit_rate_type_flat').checked = true;
            } else {
                document.getElementById('edit_rate_type_hourly').checked = true;
            }
            
            // Update title with vehicle type
            const vehicleTypeText = {
                'motor': 'Motor',
                'mobil': 'Mobil',
                'truk': 'Truk'
            }[data.vehicle_type] || 'Unknown';
            
            const titleEl = document.getElementById('edit-rate-title');
            if (titleEl) {
                titleEl.textContent = `(${vehicleTypeText})`;
            }
            
            // Toggle end hour field visibility if the function exists
            if (typeof window.toggleEndHourField === 'function') {
                window.toggleEndHourField('edit');
            }
            
            // Clean up any existing backdrops
            window.cleanupModalBackdrops();

            // Show the modal
            if (window.editRateModal) {
                window.editRateModal.show();
            } else {
                // Try to initialize on demand
                window.editRateModal = window.initializeModal('editRateModal');
                if (window.editRateModal) {
                    window.editRateModal.show();
                } else {
                    console.error('Edit rate modal not properly initialized');
                    alert('Terjadi kesalahan saat membuka modal edit. Silakan refresh halaman dan coba lagi.');
                }
            }
        } catch (error) {
            console.error('Error populating edit modal for parking rate:', error);
            alert('Terjadi kesalahan saat membuka modal edit. Silakan coba lagi.');
        }
    };

    console.log('Modal handler initialized successfully');
});
