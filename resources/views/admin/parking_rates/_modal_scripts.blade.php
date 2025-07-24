<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toast notification function
    window.showToast = function(message, type = 'info') {
        // Create toast container if it doesn't exist
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'fixed top-4 right-4 z-50 flex flex-col gap-2';
            document.body.appendChild(toastContainer);
        }
        
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `flex items-center w-full max-w-xs p-4 mb-4 rounded-lg shadow text-white ${
            type === 'error' ? 'bg-red-500' : 
            type === 'success' ? 'bg-green-500' : 
            type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500'
        }`;
        
        // Toast content
        toast.innerHTML = `
            <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-lg">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    ${type === 'error' ? 
                      '<path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>' : 
                      type === 'success' ? 
                      '<path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>' : 
                      '<circle cx="10" cy="10" r="9" stroke="currentColor" stroke-width="2" fill="none"></circle><circle cx="10" cy="6" r="1"></circle><rect x="9" y="9" width="2" height="7"></rect>'}
                </svg>
            </div>
            <div class="ml-3 text-sm font-normal">${message}</div>
            <button type="button" class="ml-auto -mx-1.5 -my-1.5 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 inline-flex h-8 w-8 text-white hover:text-gray-900 hover:bg-white" data-dismiss-target="#toast-success" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
        `;
        
        // Add to container
        toastContainer.appendChild(toast);
        
        // Add close button functionality
        const closeButton = toast.querySelector('button');
        closeButton.addEventListener('click', () => {
            toast.remove();
        });
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            toast.remove();
        }, 3000);
        
        return toast;
    };
    
    // Initialize modals
    let addRateModal, editRateModal;
    
    // Make sure Flowbite is available globally
    if (typeof window.Flowbite === 'undefined' && typeof flowbite !== 'undefined') {
        console.warn('Flowbite not found in global scope, making it available');
        window.Flowbite = {
            Modal: flowbite.Modal
        };
    }
    
    // Initialize modals using the global function
    if (window.initializeModal) {
        addRateModal = window.initializeModal('addRateModal');
        editRateModal = window.initializeModal('editRateModal');
        
        if (addRateModal) window.addRateModal = addRateModal;
        if (editRateModal) window.editRateModal = editRateModal;
    } else {
        console.error('Modal initialization function not found');
        
        // Fallback initialization
        try {
            // Function to clean up modal backdrops
            function cleanupModalBackdrops() {
                document.querySelectorAll('[modal-backdrop]').forEach(backdrop => {
                    backdrop.remove();
                });
            }
            
            // Initialize add modal
            const addModalEl = document.getElementById('addRateModal');
            if (addModalEl) {
                addRateModal = new flowbite.Modal(addModalEl, {
                    backdropClasses: 'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40',
                    onShow: () => {
                        document.body.style.overflow = 'hidden';
                        cleanupModalBackdrops();
                    },
                    onHide: () => {
                        cleanupModalBackdrops();
                        document.body.style.overflow = '';
                    }
                });
                window.addRateModal = addRateModal;
            }
    
            // Initialize edit modal
            const editModalEl = document.getElementById('editRateModal');
            if (editModalEl) {
                // Check if we already have a custom implementation
                if (window.editRateModal && typeof window.editRateModal.show === 'function') {
                    console.log('Using custom modal implementation');
                } else {
                    console.log('Initializing with flowbite');
                    try {
                        editRateModal = new flowbite.Modal(editModalEl, {
                            backdropClasses: 'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40',
                            onShow: () => {
                                document.body.style.overflow = 'hidden';
                                cleanupModalBackdrops();
                            },
                            onHide: () => {
                                cleanupModalBackdrops();
                                document.body.style.overflow = '';
                            }
                        });
                        window.editRateModal = editRateModal;
                    } catch (error) {
                        console.error('Could not initialize with flowbite:', error);
                        // Provide a fallback
                        window.editRateModal = {
                            show: () => {
                                editModalEl.classList.remove('hidden');
                                document.body.classList.add('overflow-hidden');
                            },
                            hide: () => {
                                editModalEl.classList.add('hidden');
                                document.body.classList.remove('overflow-hidden');
                                document.querySelectorAll('[modal-backdrop]').forEach(el => el.remove());
                            }
                        };
                    }
                }
            }
        } catch (error) {
            console.error('Error in fallback modal initialization:', error);
        }
    }
    
    // Add event listeners for modal hide buttons
    document.querySelectorAll('[data-modal-hide]').forEach(button => {
        button.addEventListener('click', () => {
            const modalId = button.getAttribute('data-modal-hide');
            if (modalId === 'addRateModal' && window.addRateModal) {
                window.addRateModal.hide();
            } else if (modalId === 'editRateModal' && window.editRateModal) {
                window.editRateModal.hide();
            }
        });
    });
    
    // Add event listeners to edit buttons
    document.querySelectorAll('.edit-rate-btn').forEach(button => {
        button.addEventListener('click', function() {
            const rateId = this.getAttribute('data-id');
            if (!rateId) {
                console.error('No rate ID found on button');
                return;
            }
            
            loadRateData(rateId);
        });
    });
    
    // Load rate data from API
    async function loadRateData(rateId) {
        try {
            // Show loading indicator
            const loadingToast = showToast('Loading...', 'info');
            
            const response = await fetch(`/admin/parking-rates/${rateId}`);
            
            // Hide loading indicator
            if (loadingToast) loadingToast.remove();
            
            if (!response.ok) {
                throw new Error('Failed to fetch rate data');
            }
            
            const data = await response.json();
            if (!data) {
                throw new Error('No data returned from server');
            }
            
            // Set form action with correct rate ID
            const form = document.getElementById('editRateForm');
            form.action = `/admin/parking-rates/${data.id}`;
            
            // Set values to form fields
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_vehicle_type').value = data.vehicle_type;
            document.getElementById('edit_duration_start_hour').value = data.duration_start_hour;
            document.getElementById('edit_duration_end_hour').value = data.duration_end_hour || '';
            document.getElementById('edit_rate').value = data.rate;
            
            // Set the correct radio button for is_flat_rate
            if (data.is_flat_rate) {
                document.getElementById('edit_rate_type_flat').checked = true;
            } else {
                document.getElementById('edit_rate_type_hourly').checked = true;
            }
            
            // Clean up any existing backdrops
            document.querySelectorAll('[modal-backdrop]').forEach(backdrop => {
                backdrop.remove();
            });

            // Show the modal
            try {
                const modalElement = document.getElementById('editRateModal');
                
                // Create modal instance using the available Flowbite API
                let modal;
                
                if (typeof flowbite !== 'undefined' && flowbite.Modal) {
                    modal = new flowbite.Modal(modalElement, {
                        placement: 'center',
                        backdrop: 'dynamic',
                        backdropClasses: 'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40',
                    });
                } else if (typeof Flowbite !== 'undefined' && Flowbite.Modal) {
                    modal = new Flowbite.Modal(modalElement, {
                        placement: 'center',
                        backdrop: 'dynamic',
                        backdropClasses: 'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40',
                    });
                } else {
                    // If all else fails, try to trigger the modal using data attributes
                    const $targetEl = document.getElementById('editRateModal');
                    $targetEl.classList.remove('hidden');
                    
                    // Create backdrop manually
                    const backdropEl = document.createElement('div');
                    backdropEl.setAttribute('modal-backdrop', '');
                    backdropEl.className = 'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40';
                    document.body.appendChild(backdropEl);
                    
                    console.warn('Using fallback modal opening method');
                    
                    // Return simple object with show/hide methods
                    window.editRateModal = {
                        show: function() {
                            $targetEl.classList.remove('hidden');
                        },
                        hide: function() {
                            $targetEl.classList.add('hidden');
                            document.querySelectorAll('[modal-backdrop]').forEach(el => el.remove());
                        }
                    };
                    
                    return;
                }
                
                // Store modal instance globally
                window.editRateModal = modal;
                
                // Show the modal
                modal.show();
            } catch (error) {
                console.error('Error initializing modal:', error);
                showToast('Terjadi kesalahan saat membuka modal edit. Silakan refresh halaman.', 'error');
            }
            
            // Set form action with correct rate ID
            const form = document.getElementById('editRateForm');
            form.action = `/admin/parking-rates/${data.id}`;
            
            // Set values to form fields
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_vehicle_type').value = data.vehicle_type;
            document.getElementById('edit_duration_start_hour').value = data.duration_start_hour;
            document.getElementById('edit_duration_end_hour').value = data.duration_end_hour || '';
            document.getElementById('edit_rate').value = data.rate;
            
            // Set the correct radio button for is_flat_rate
            if (data.is_flat_rate) {
                document.getElementById('edit_rate_type_flat').checked = true;
            } else {
                document.getElementById('edit_rate_type_hourly').checked = true;
            }
            
            // Clean up any existing backdrops
            document.querySelectorAll('[modal-backdrop]').forEach(backdrop => {
                backdrop.remove();
            });

            // Show the modal
            if (typeof flowbite !== 'undefined') {
                // Initialize a new modal instance if needed
                const modalElement = document.getElementById('editRateModal');
                const modal = new flowbite.Modal(modalElement, {
                    placement: 'center',
                    backdrop: 'dynamic',
                    backdropClasses: 'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40',
                });
                
                // Show the modal
                modal.show();
                window.editRateModal = modal;
            } else {
                console.error('Flowbite library not found');
                alert('Terjadi kesalahan saat membuka modal edit. Silakan refresh halaman dan coba lagi.');
            }
        } catch (error) {
            console.error('Error populating edit modal:', error);
            alert('Terjadi kesalahan saat membuka modal edit. Silakan coba lagi.');
        }
    };
});
</script>
