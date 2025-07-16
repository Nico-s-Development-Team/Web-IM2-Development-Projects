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

document.querySelectorAll('.add-to-cart').forEach(button => {
  button.addEventListener('click', () => {
    const productCard = button.closest('div');
    const name = productCard.querySelector('h4').innerText.trim();
    const price = parseFloat(productCard.querySelector('.text-green-600').innerText.replace('₱', '').trim());
    const image = productCard.querySelector('img')?.getAttribute('src') || 'img/default.jpg';

    const existing = basket.find(item => item.name === name);
    if (existing) {
      existing.quantity += 1;
    } else {
      basket.push({ name, price, quantity: 1, image });
    }

    localStorage.setItem('basket', JSON.stringify(basket));
    renderBasket();
  });
});

function renderBasket() {
  localStorage.setItem('basket', JSON.stringify(basket));
  const basketContainer = document.querySelector('aside.w-64');
  let total = 0;

  const html = basket.map((item, index) => {
    total += item.price * item.quantity;
    return `
    <div class="w-full bg-white rounded-xl shadow p-3 mb-3 border border-gray-200 flex gap-3 items-start">
        <img src="${item.image || 'img/default.jpg'}" alt="${item.name}" class="w-12 h-12 object-cover rounded-md border border-gray-300">

        <div class="flex-1 flex justify-between items-start min-w-0">
        <div class="min-w-0">
        <p class="font-semibold text-gray-800 text-sm break-words leading-snug">${item.name}</p>
        <div class="flex items-center gap-2 mt-1">
            <button class="decrease-qty px-2 py-1 text-gray-600 hover:text-red-500 rounded border border-gray-300 hover:border-red-400" data-index="${index}">➖</button>
            <span class="text-gray-700">x${item.quantity}</span>
            <button class="increase-qty px-2 py-1 text-gray-600 hover:text-green-600 rounded border border-gray-300 hover:border-green-400" data-index="${index}">➕</button>
        </div>
        </div>

        <div class="text-right">
            <p class="text-sm text-gray-700 font-medium">₱${(item.price * item.quantity).toFixed(2)}</p>
        </div>
        </div>
    </div>
    `;
  }).join('');

  basketContainer.innerHTML = `
  <div id="basket-items" class="mb-24"> <!-- adds bottom space for fixed button -->
    <h3 class="text-center text-gray-600 text-lg mb-2">Your Basket</h3>
    ${html || '<p class="text-sm text-center text-gray-500">Basket is empty.</p>'}
    ${basket.length > 0 ? `
      <button id="clearBasket" class="flex items-center gap-2 text-sm font-medium text-red-500 hover:text-white border border-red-500 hover:bg-red-500 px-3 py-1 rounded transition mb-3 w-full justify-center">🗑️ Clear All</button>
    ` : ''}
  </div>

  <div id="basket-footer" class="w-full mt-4">
  <div class="flex justify-between w-full font-semibold mt-2">
      <span>Total:</span>
      <span id="basket-total">₱${total.toFixed(2)}</span>
    </div>
  <button id="checkoutBtn" class="bg-red-500 hover:bg-red-600 text-white w-full py-2 rounded transition shadow-md ${basket.length === 0 ? 'opacity-50 cursor-not-allowed' : ''}" ${basket.length === 0 ? 'disabled' : ''}>
    Checkout
  </button>
</div>
`;

  // Event Listeners
  document.getElementById('checkoutBtn')?.addEventListener('click', handleCheckout);

  document.querySelectorAll('.remove-item').forEach(button => {
    button.addEventListener('click', () => {
      const index = parseInt(button.getAttribute('data-index'));
      basket.splice(index, 1);
      localStorage.setItem('basket', JSON.stringify(basket));
      renderBasket();
    });
  });

  document.querySelectorAll('.increase-qty').forEach(button => {
    button.addEventListener('click', () => {
      const index = parseInt(button.getAttribute('data-index'));
      basket[index].quantity++;
      localStorage.setItem('basket', JSON.stringify(basket));
      renderBasket();
    });
  });

  document.querySelectorAll('.decrease-qty').forEach(button => {
    button.addEventListener('click', () => {
      const index = parseInt(button.getAttribute('data-index'));
      if (basket[index].quantity > 1) {
        basket[index].quantity--;
      } else {
        basket.splice(index, 1);
      }
      localStorage.setItem('basket', JSON.stringify(basket));
      renderBasket();
    });
  });

    // Modal confirmation for Clear All
    document.getElementById('clearBasket')?.addEventListener('click', () => {
        document.getElementById('clearModal').classList.remove('hidden');
    });

    // Cancel button hides modal
    document.getElementById('cancelClear')?.addEventListener('click', () => {
        document.getElementById('clearModal').classList.add('hidden');
    });

    // Confirm button clears basket and hides modal
    document.getElementById('confirmClear')?.addEventListener('click', () => {
    basket = [];
    localStorage.setItem('basket', JSON.stringify(basket));
    renderBasket();
    document.getElementById('clearModal').classList.add('hidden');
    });
}

// Event: Cancel closes the modal
document.getElementById('cancelCheckout')?.addEventListener('click', () => {
  document.getElementById('checkoutModal').classList.add('hidden');
});

// Event: Go to Review (redirects to reviewOrderPage.html)
document.getElementById('goToReview')?.addEventListener('click', () => {
  window.location.href = 'reviewOrderPage.php';
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