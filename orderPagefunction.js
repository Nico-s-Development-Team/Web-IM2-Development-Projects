// Sidebar active link toggle
document.addEventListener('DOMContentLoaded', () => {
  const sidebarItems = document.querySelectorAll('#sidebar-menu li');
  const closeModalBtn = document.getElementById('closeModal');
  
  if (closeModalBtn) {
    closeModalBtn.addEventListener('click', () => {
      document.getElementById('checkoutModal').classList.add('hidden');
    });
  }

  sidebarItems.forEach(item => {
    item.addEventListener('click', () => {
      document.querySelector('#sidebar-menu li.active')?.classList.remove('active');
      item.classList.add('active');
    });
  });
});

// Profile sidebar toggle
const toggleBtn = document.getElementById('profile-toggle');
const profileSidebar = document.getElementById('profile-sidebar');

toggleBtn.addEventListener('click', (e) => {
  e.stopPropagation();
  profileSidebar.classList.toggle('translate-x-full');
  profileSidebar.classList.toggle('translate-x-0');
});

document.addEventListener('click', (event) => {
  const clickedInsideSidebar = profileSidebar.contains(event.target);
  const clickedToggleButton = toggleBtn.contains(event.target);

  if (
    !clickedInsideSidebar &&
    !clickedToggleButton &&
    profileSidebar.classList.contains('translate-x-0')
  ) {
    profileSidebar.classList.add('translate-x-full');
    profileSidebar.classList.remove('translate-x-0');
  }
});

// Smooth scroll to section
document.addEventListener("DOMContentLoaded", function () {
  const links = document.querySelectorAll('#sidebar-menu a');
  const mainContainer = document.querySelector('main');

  links.forEach(link => {
    link.addEventListener('click', function (e) {
      e.preventDefault();
      const targetId = this.getAttribute("href").slice(1);
      const target = document.getElementById(targetId);
      if (target) {
        mainContainer.scrollTo({
          top: target.offsetTop - mainContainer.offsetTop - 70,
          behavior: "smooth"
        });
      }
    });
  });
});

// ===============================
// 🛒 Add to Cart and Basket Logic
// ===============================

let basket = JSON.parse(localStorage.getItem('basket')) || [];
renderBasket();

document.addEventListener('click', function (event) {
  if (event.target.classList.contains('add-to-cart')) {
    const button = event.target;
    const productCard = button.closest('.bg-white');

    const id = button.dataset.id;
    const name = button.dataset.name;
    const price = parseFloat(button.dataset.price);
    const image = productCard.querySelector('img')?.getAttribute('src') || 'img/burger.png';

    const existing = basket.find(item => item.name === name); // FIXED: compare IDs properly
    if (existing) {
      existing.quantity += 1;
    } else {
      basket.push({ id, name, price, quantity: 1, image });
    }

    localStorage.setItem('basket', JSON.stringify(basket));
    renderBasket();
    console.log("Basket now:", basket); // DEBUG
  }
});


function renderBasket() {
  localStorage.setItem('basket', JSON.stringify(basket));

  const basketContainer = document.getElementById('basket-items');
  const basketTotal = document.getElementById('basket-total');
  const checkoutButton = document.getElementById('checkout-button');
  const basketIcon = document.getElementById('basket-icon');

  let total = 0;

  if (basket.length === 0) {
    if (basketIcon) basketIcon.classList.remove('hidden'); // SHOW icon when empty

    basketContainer.innerHTML = `
      <div class="flex justify-between items-center border-b pb-2">
        <div>
          <p class="font-medium">No items yet</p>
          <p class="text-xs text-gray-400">Your cart is waiting...</p>
        </div>
        <div class="text-sm text-gray-500">₱0</div>
      </div>
    `;
    basketTotal.textContent = '₱0.00';
    checkoutButton.disabled = true;
    checkoutButton.classList.add('opacity-50', 'cursor-not-allowed');
    return;
  }

  if (basketIcon) basketIcon.classList.add('hidden'); // HIDE icon when items exist

  // Render basket items
  basketContainer.innerHTML = basket.map((item, index) => {
    total += item.price * item.quantity;
    return `
      <div class="flex justify-between items-start border-b pb-2 gap-3">
        <img src="${item.image || 'img/default.jpg'}" alt="${item.name}" class="w-12 h-12 rounded border border-gray-300 object-cover">
        <div class="flex-1 min-w-0">
          <p class="font-medium text-sm text-gray-800 break-words leading-tight">${item.name}</p>
          <div class="flex items-center gap-1 mt-1 text-xs">
            <button class="decrease-qty px-2 py-1 text-gray-600 hover:text-red-500 rounded border border-gray-300 hover:border-red-400" data-index="${index}">➖</button>
            <span class="text-gray-700">x${item.quantity}</span>
            <button class="increase-qty px-2 py-1 text-gray-600 hover:text-green-600 rounded border border-gray-300 hover:border-green-400" data-index="${index}">➕</button>
          </div>
        </div>
        <div class="text-sm text-gray-700 font-medium whitespace-nowrap">₱${(item.price * item.quantity).toFixed(2)}</div>
      </div>
    `;
  }).join('');

  basketContainer.innerHTML += `
    <button id="clearBasket" class="flex items-center gap-2 text-xs font-medium text-red-500 hover:text-white border border-red-500 hover:bg-red-500 px-2 py-1 rounded transition mt-3 w-full justify-center">
      🗑️ Clear All
    </button>
  `;

  basketTotal.textContent = `₱${total.toFixed(2)}`;
  checkoutButton.disabled = false;
  checkoutButton.classList.remove('opacity-50', 'cursor-not-allowed');

  // Event Listeners for quantity
  document.querySelectorAll('.increase-qty').forEach(btn => {
    btn.addEventListener('click', () => {
      const index = parseInt(btn.dataset.index);
      basket[index].quantity++;
      renderBasket();
    });
  });

  document.querySelectorAll('.decrease-qty').forEach(btn => {
    btn.addEventListener('click', () => {
      const index = parseInt(btn.dataset.index);
      if (basket[index].quantity > 1) {
        basket[index].quantity--;
      } else {
        basket.splice(index, 1);
      }
      renderBasket();
    });
  });

  // Checkout button
  checkoutButton.addEventListener('click', handleCheckout);

  // Clear All Modal triggers
  document.getElementById('clearBasket')?.addEventListener('click', () => {
    document.getElementById('clearModal')?.classList.remove('hidden');
  });

  document.getElementById('cancelClear')?.addEventListener('click', () => {
    document.getElementById('clearModal')?.classList.add('hidden');
  });

  document.getElementById('confirmClear')?.addEventListener('click', () => {
    basket = [];
    localStorage.setItem('basket', JSON.stringify(basket));
    renderBasket();
    document.getElementById('clearModal')?.classList.add('hidden');
  });
}


// Event: Cancel closes the modal
document.getElementById('cancelCheckout')?.addEventListener('click', () => {
  document.getElementById('checkoutModal').classList.add('hidden');
});

// Event: Go to Review (redirects to reviewOrderPage.html)
document.getElementById('goToReview')?.addEventListener('click', () => {
  const overlay = document.getElementById('loading-overlay');
  if (overlay) {
    overlay.classList.remove('hidden'); // Show loading screen
  }

  // Slight delay so overlay is visible before redirect
  setTimeout(() => {
    window.location.href = 'reviewOrderPage.php';
  }, 300);
});


// Fill modal with basket items
function updateCheckoutSummary() {
  const summary = document.getElementById('checkoutSummary');
  const totalElem = document.getElementById('checkoutTotal');

  if (!summary || !totalElem) return;

  if (basket.length === 0) {
    summary.innerHTML = `<p class="text-center text-gray-500">Your basket is empty.</p>`;
    totalElem.textContent = "₱0.00";
    return;
  }

  let total = 0;
  let html = '';

  basket.forEach(item => {
    const subtotal = item.price * item.quantity;
    total += subtotal;
    html += `
      <div class="flex justify-between items-start">
        <div>
          <p class="font-medium text-gray-800">${item.quantity}x ${item.name}</p>
          <p class="text-sm text-gray-500">₱${item.price.toFixed(2)} each</p>
        </div>
        <div class="text-right font-medium text-gray-700">₱${subtotal.toFixed(2)}</div>
      </div>
    `;
  });

  summary.innerHTML = html;
  totalElem.textContent = `₱${total.toFixed(2)}`;
}


// ===============================
// ✅ Checkout Function
// ===============================

function handleCheckout() {
  updateCheckoutSummary();
  document.getElementById('checkoutModal').classList.remove('hidden');
}

// async function handleCheckout() {
//   const userId = currentUserId;

//   try {
//     const response = await fetch('checkout.php', {
//       method: 'POST',
//       headers: {
//         'Content-Type': 'application/json'
//       },
//       body: JSON.stringify({ userId, basket })
//     });

//     const result = await response.json();

//     if (result.success) {
//       basket = [];
//       localStorage.setItem('basket', JSON.stringify(basket));
//       renderBasket();

//       // ✅ Show the modal instead of alert
//       document.getElementById('checkoutModal').classList.remove('hidden');
//     } else {
//       console.error("Server error:", result.error);
//       alert("Error placing order: " + result.error);
//     }
//   } catch (error) {
//     console.error("Checkout request failed:", error);
//     alert("Something went wrong. Please try again.");
//   }
// }