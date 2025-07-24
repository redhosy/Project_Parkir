/**
 * Global Modal Initialization Script
 * This script ensures Flowbite is properly available and initializes modals
 */
document.addEventListener('DOMContentLoaded', function() {
    // Make sure Flowbite is available globally for modals
    if (typeof flowbite !== 'undefined' && !window.Flowbite) {
        window.Flowbite = {
            Modal: flowbite.Modal
        };
        console.log('Flowbite initialized globally');
    }
    
    // Function to clean up modal backdrops
    function cleanupModalBackdrops() {
        document.querySelectorAll('[modal-backdrop]').forEach(backdrop => {
            backdrop.remove();
        });
    }
    
    // Initialize modals for parking slots
    const editParkingSlotModalEl = document.getElementById('editParkingSlotModal');
    if (editParkingSlotModalEl && typeof Flowbite !== 'undefined') {
        const editModal = new Flowbite.Modal(editParkingSlotModalEl, {
            backdropClasses: 'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40',
            onShow: () => {
                // Ensure body overflow is hidden to prevent scrolling behind modal
                document.body.style.overflow = 'hidden';
                cleanupModalBackdrops();
            },
            onHide: () => {
                // Remove modal backdrop manually when modal is closed
                cleanupModalBackdrops();
                // Restore body scrolling
                document.body.style.overflow = '';
            }
        });
        window.editParkingSlotModal = editModal;
    }
    
    // Initialize modals for parking rates
    const editRateModalEl = document.getElementById('editRateModal');
    if (editRateModalEl && typeof Flowbite !== 'undefined') {
        const editModal = new Flowbite.Modal(editRateModalEl, {
            backdropClasses: 'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40',
            onShow: () => {
                // Ensure body overflow is hidden to prevent scrolling behind modal
                document.body.style.overflow = 'hidden';
                cleanupModalBackdrops();
            },
            onHide: () => {
                // Remove modal backdrop manually when modal is closed
                cleanupModalBackdrops();
                // Restore body scrolling
                document.body.style.overflow = '';
            }
        });
        window.editRateModal = editModal;
    }
    
    // Add improved click handlers for modal close buttons
    document.querySelectorAll('[data-modal-hide]').forEach(button => {
        button.addEventListener('click', function() {
            const modalId = this.getAttribute('data-modal-hide');
            const modalInstance = window[modalId === 'editRateModal' ? 'editRateModal' : 'editParkingSlotModal'];
            
            if (modalInstance) {
                modalInstance.hide();
            }
            
            // Extra cleanup
            cleanupModalBackdrops();
        });
    });
});
