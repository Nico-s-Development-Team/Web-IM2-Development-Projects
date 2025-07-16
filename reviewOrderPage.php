<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <script>
  const currentUserId = <?= json_encode($_SESSION['customer_id'] ?? null); ?>;
</script>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Review & Payment Modal</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @keyframes modalFadeIn {
      from { opacity: 0; transform: scale(0.95); }
      to { opacity: 1; transform: scale(1); }
    }
    .animate-modalFadeIn {
      animation: modalFadeIn 0.3s ease-out;
    }
  </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

  <!-- Review & Payment Modal -->
  <div id="reviewModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-40 flex justify-center items-center px-4">
    <div class="bg-white w-full max-w-xl rounded-lg shadow-lg p-6 relative animate-modalFadeIn">

      <!-- Step Navigation -->
      <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2">
          <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold bg-gray-300 text-white">1</div>
          <span class="text-sm text-gray-500">Menu</span>
        </div>
        <div class="flex-1 h-1 mx-2 bg-gray-300 rounded"></div>
        <div class="flex items-center gap-2">
          <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold bg-gray-300 text-white">2</div>
          <span class="text-sm text-gray-500">Basket</span>
        </div>
        <div class="flex-1 h-1 mx-2 bg-red-300 rounded"></div>
        <div class="flex items-center gap-2">
          <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold bg-red-500 text-white ring-2 ring-red-300">3</div>
          <span class="text-sm text-red-600 font-medium">Checkout</span>
        </div>
      </div>

      <!-- Order Summary -->
      <div class="mb-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Order Summary</h3>
        <div id="order-summary" class="space-y-3 max-h-48 overflow-y-auto border-t pt-3">
          <!-- JS will populate -->
        </div>
        <div class="flex justify-between items-center mt-4 border-t pt-4 font-semibold">
          <span>Total:</span>
          <span id="order-total" class="text-red-500">₱0.00</span>
        </div>
      </div>

      <!-- Delivery Method -->
      <div class="mb-4">
        <label class="text-sm font-medium text-gray-700 block mb-2">Delivery Method</label>
        <div class="flex gap-4">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" name="deliveryMethod" value="pickup" class="text-red-500 focus:ring-red-400" checked>
            <span class="text-sm text-gray-700">Pickup</span>
          </label>
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" name="deliveryMethod" value="delivery" class="text-red-500 focus:ring-red-400">
            <span class="text-sm text-gray-700">Delivery</span>
          </label>
        </div>
      </div>

      <!-- Address Section -->
      <div id="addressSection" class="mb-4 hidden">
        <label class="text-sm font-medium text-gray-700 block mb-2">Choose Delivery Address</label>
        <select id="deliveryAddress" class="w-full border rounded px-3 py-2 text-sm border-gray-300 focus:ring-red-400 focus:border-red-400">
          <option value="123 Food St., Cebu City, PH">123 Food St., Cebu City, PH</option>
          <option value="45 Mango Ave., Cebu City, PH">45 Mango Ave., Cebu City, PH</option>
          <option value="Custom">Add New Address</option>
        </select>
        <input id="customAddress" class="w-full border rounded px-3 py-2 text-sm mt-2 hidden border-gray-300 focus:ring-red-400 focus:border-red-400" placeholder="Enter new address...">
      </div>

      <!-- Payment Method -->
      <div class="mb-4">
        <label class="text-sm font-medium text-gray-700 block mb-2">Payment Method</label>
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="radio" name="paymentMethod" value="cash" class="text-red-500 focus:ring-red-400" checked>
          <span class="text-sm text-gray-700">Cash</span>
        </label>
      </div>

      <!-- Terms and Conditions -->
      <div class="mb-4 text-sm text-gray-600">
        <label class="flex items-start gap-2">
          <input type="checkbox" id="agreeTerms" class="mt-1 text-red-500 focus:ring-red-400">
          <span>I agree to allow Nico's Food Delivery to share my order and personal details with the restaurant for order fulfillment.</span>
        </label>
      </div>

      <!-- Actions -->
      <div class="flex justify-end gap-3 mt-6">
        <button id="backToBasket" class="px-4 py-2 text-sm border rounded text-gray-600 hover:text-red-500 hover:border-red-500">Back</button>
        <button id="placeOrderBtn" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 text-sm disabled:opacity-50" disabled>Place Order</button>
      </div>
    </div>
  </div>

  <!-- JavaScript -->
  <script>
    const deliveryRadios = document.querySelectorAll('input[name="deliveryMethod"]');
    const addressSection = document.getElementById('addressSection');
    const addressDropdown = document.getElementById('deliveryAddress');
    const customAddressInput = document.getElementById('customAddress');
    const agreeTerms = document.getElementById('agreeTerms');
    const placeOrderBtn = document.getElementById('placeOrderBtn');
    const reviewModal = document.getElementById('reviewModal');

    document.addEventListener("DOMContentLoaded", () => {
  const reviewModal = document.getElementById('reviewModal');
  reviewModal.classList.remove('hidden');

    // Populate basket content dynamically from localStorage
  const summary = document.getElementById('order-summary');
  const totalElem = document.getElementById('order-total');
  const basket = JSON.parse(localStorage.getItem('basket')) || [];

  let total = 0;
  let html = '';

  basket.forEach(item => {
    const subtotal = item.price * item.quantity;
    total += subtotal;
    html += `
      <div class="flex justify-between items-center border-b py-2">
        <div>
          <p class="font-medium text-gray-800">${item.quantity}x ${item.name}</p>
          <p class="text-sm text-gray-500">₱${item.price.toFixed(2)} each</p>
        </div>
        <div class="text-right text-gray-700 font-semibold">₱${subtotal.toFixed(2)}</div>
      </div>
    `;
  });

  summary.innerHTML = basket.length ? html : '<p class="text-gray-500 text-sm">Your basket is empty.</p>';
  totalElem.textContent = `₱${total.toFixed(2)}`;

  deliveryRadios.forEach(radio => {
  radio.addEventListener('change', () => {
    if (radio.value === 'delivery' && radio.checked) {
      addressSection.classList.remove('hidden');
    } else if (radio.value === 'pickup' && radio.checked) {
      addressSection.classList.add('hidden');
    }
  });
});

  agreeTerms.addEventListener('change', () => {
    placeOrderBtn.disabled = !agreeTerms.checked;
  });

    // Enable order placement
  placeOrderBtn.addEventListener('click', async () => {
    const basket = JSON.parse(localStorage.getItem('basket')) || [];
    const userId = currentUserId; // TODO: Replace with session-based user ID if available

    const response = await fetch('checkout.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        userId,
        basket
      })
    });

    const result = await response.json();
    if (result.success) {
      alert(`Order placed successfully! Order ID: ${result.orderId}`);
      localStorage.removeItem('basket');
      window.location.href = 'deliveryPage.php?order_id=' + result.orderId;
    } else {
      alert('Error placing order: ' + result.error);
    }
  });
});
  </script>
</body>
</html>
