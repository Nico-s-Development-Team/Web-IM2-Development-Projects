document.addEventListener("DOMContentLoaded", () => {
    const basket = JSON.parse(localStorage.getItem('basket')) || [];

    const summary = document.getElementById('order-summary');
    const totalElem = document.getElementById('order-total');

    if (!basket.length) {
      summary.innerHTML = "<p class='text-gray-500'>Your basket is empty.</p>";
      totalElem.textContent = "₱0.00";
      return;
    }

    let total = 0;
    let html = "";

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

    summary.innerHTML = html;
    totalElem.textContent = `₱${total.toFixed(2)}`;
  });
