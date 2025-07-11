// Get the modal elements
const signupModal = document.getElementById('signup');
const loginModal = document.getElementById('login');

// Get the close buttons
const closeButtons = document.querySelectorAll('.close-signup, .close-login');

// Get all "Order Now" buttons
const orderButtons = document.querySelectorAll('.order-btn');

// Get switch links and history button
const loginLink = document.getElementById('switch-to-login');
const signupLink = document.getElementById('switch-to-signup');
const historyBtn = document.getElementById('btn-history');

// Utility functions to show/hide modals
function showModal(modal) {
  modal.classList.remove('hidden');
  modal.classList.add('fixed', 'inset-0', 'flex', 'items-center', 'justify-center', 'z-50', 'bg-black', 'bg-opacity-50');
}

function hideModal(modal) {
  modal.classList.add('hidden');
  modal.classList.remove('flex', 'fixed', 'inset-0', 'bg-black', 'bg-opacity-50');
}

function openLoginModal() {
  showModal(loginModal);
  hideModal(signupModal);
}

function openSignupModal() {
  showModal(signupModal);
  hideModal(loginModal);
}

function closeAllModals() {
  hideModal(signupModal);
  hideModal(loginModal);
}

// Attach "Order Now" button events
orderButtons.forEach(button => {
  button.addEventListener('click', openLoginModal);
});

// Attach event for order history
if (historyBtn) {
  historyBtn.addEventListener('click', openLoginModal);
}

// Link switchers
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

// Close modals with close (X) button
closeButtons.forEach(btn => {
  btn.addEventListener('click', closeAllModals);
});

// Close modals when clicking outside the content
window.addEventListener('click', (e) => {
  if (e.target === signupModal || e.target === loginModal) {
    closeAllModals();
  }
});
