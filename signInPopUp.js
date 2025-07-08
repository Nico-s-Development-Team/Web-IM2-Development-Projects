
// Get the modal
const signupModal = document.getElementById('signup');
const loginModal = document.getElementById('login');

// Get the close button
const closeBtn = document.querySelectorAll('.close');  

// Get ALL buttons that should open the modal
const orderButtons = document.querySelectorAll('.order-btn');

// links
const historyBtn = document.getElementById('btn-history'); 
const loginLink = document.getElementById('switch-to-login');
const signupLink = document.getElementById('switch-to-signup');

// Function to open modal
function openSignupModal() {
    signupModal.style.display = 'flex';
    loginModal.style.display = 'none';
}

function openLoginModal() {
    loginModal.style.display  = 'flex';
    signupModal.style.display = 'none';
}

// close All modals
function closeAllModals() {
    signupModal.style.display = 'none';
    loginModal.style.display  = 'none';
}


// Attach event to every 'Order Now' button
orderButtons.forEach(button => {
    button.addEventListener('click', openSignupModal);
});

// Get id to open modal
if (historyBtn) historyBtn.addEventListener('click', openSignupModal);

if (loginLink) {
    loginLink.addEventListener('click', (e) => {
        e.preventDefault();
        openLoginModal();
    });
}
if (signupLink) {
    signupLink.addEventListener('click', (e) => {
        e.preventDefault();
        openSignupModal();
    });
}

// Close when clicking the close (X) button
closeBtn.forEach(btn => {
    btn.addEventListener('click', closeAllModals);
});

// Close when clicking outside the modal content
window.addEventListener('click', (e) => {
    if (e.target === signupModal || e.target === loginModal) {
        closeAllModals();
    }

});
