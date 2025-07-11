<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nico's Food Delivery - Order</title>
  <link rel="stylesheet" href="orderPageStyle.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Open+Sans:wght@400;500&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Open Sans', sans-serif;
      background-color: #FFF8F8;
    }
    h1, h2, h3 {
      font-family: 'Poppins', sans-serif;
    }
    .fade-in {
      animation: fadeIn 0.6s ease-in;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    @keyframes modalFadeIn {
      from { opacity: 0; transform: scale(0.95); }
      to { opacity: 1; transform: scale(1); }
    }
    .animate-modalFadeIn {
      animation: modalFadeIn 0.2s ease-out;
    }
  </style>
</head>
<body class="bg-[#FFF8F8] overflow-y-auto fade-in">

  <!-- Navbar -->
<header class="bg-white shadow-md border-b border-gray-300 h-16 flex items-center justify-between px-8 fixed top-0 w-full z-50">
  <div class="flex items-center gap-3">
    <img src="img/logo.png" alt="Burger King" class="w-10 h-10 rounded-full object-cover">
    <span class="text-xl font-bold text-gray-800">Nico's Food Delivery</span>
  </div>
  <div class="flex items-center gap-4">
    <button id="profile-toggle" class="bg-white p-1.5 rounded-full shadow-sm hover:bg-gray-100">
      <img src="img/user.jpg" alt="Profile" class="w-9 h-9 rounded-full">
    </button>
  </div>
</header>

  <div class="flex pt-16 min-h-screen">
    <!-- Sidebar -->
     <div class="flex px-6 gap-4">
        <aside class="w-56 bg-white border-r border-gray-300 p-4 flex-shrink-0 overflow-hidden mt-6 mb-6 rounded-md shadow">
        <h3 class="text-red-500 mb-4 text-lg font-semibold">MENU DEALS MEAL LIST</h3>
        <ul id="sidebar-menu" class="space-y-2 text-sm text-gray-800">
          <a href="#todays-offer"><li class="p-2 pl-4 border-l-4 border-transparent cursor-pointer transition-all">Today's Offer</li></a>
          <a href="#group-meals"><li class="p-2 pl-4 border-l-4 border-transparent cursor-pointer transition-all">Group Meals</li></a>
          <a href="#pork-dish"><li class="p-2 pl-4 border-l-4 border-transparent cursor-pointer transition-all">Pork Dish</li></a>
          <a href="#noodles"><li class="p-2 pl-4 border-l-4 border-transparent cursor-pointer transition-all">Noodles</li></a>
          <a href="#rice-meals"><li class="p-2 pl-4 border-l-4 border-transparent cursor-pointer transition-all">Rice Meals</li></a>
          <a href="#breakfast-meals"><li class="p-2 pl-4 border-l-4 border-transparent cursor-pointer transition-all">Breakfast Meals</li></a>
      </ul>
    </aside>
     </div>
    

    <!-- Main Content -->
    <main class="flex-1 max-h-[calc(100vh-4rem)] overflow-y-auto p-6">
      <!-- Search Bar -->
    <!-- Search Bar -->
    <div class="w-full flex justify-center mb-6">
      <input
        type="text"
        placeholder="Search dishes or meals..."
        class="w-full max-w-md px-4 py-1.5 rounded-full border border-gray-300 shadow-sm text-sm focus:outline-none focus:ring-2 focus:ring-red-400"
      />
    </div>

      <h2 id="todays-offer" class="text-xl font-semibold mb-6 text-gray-900">Today's Offer</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Example Product Card -->
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex flex-col items-center shadow hover:shadow-lg transition-transform transform hover:scale-105 duration-300">
          <img src="img/lumpia.jpg" alt="Fried Chicken" class="w-28 h-28 object-contain mb-3 rounded">
          <h4 class="text-base font-medium text-gray-900">Fried Chicken</h4>
          <p class="text-sm text-gray-500 text-center">Crispy, golden perfection wrapped around savory seasoned meat</p>
          <div class="text-green-600 font-bold mt-2">₱105</div>
          <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white rounded-full px-3 py-1 mt-2 text-lg transition">+</button>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex flex-col items-center shadow hover:shadow-lg transition-transform transform hover:scale-105 duration-300">
          <img src="img/lumpia.jpg" alt="Lumpia Shanghai" class="w-28 h-28 object-contain mb-3 rounded">
          <h4 class="text-base font-medium text-gray-900">Lumpia Shanghai</h4>
          <p class="text-sm text-gray-500 text-center">Crispy, golden perfection wrapped around savory seasoned meat</p>
          <div class="text-green-600 font-bold mt-2">₱105</div>
          <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white rounded-full px-3 py-1 mt-2 text-lg transition">+</button>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex flex-col items-center shadow hover:shadow-lg transition-transform transform hover:scale-105 duration-300">
          <img src="img/lumpia.jpg" alt="Pork BBQ" class="w-28 h-28 object-contain mb-3 rounded">
          <h4 class="text-base font-medium text-gray-900">Pork BBQ</h4>
          <p class="text-sm text-gray-500 text-center">Crispy, golden perfection wrapped around savory seasoned meat</p>
          <div class="text-green-600 font-bold mt-2">₱105</div>
          <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white rounded-full px-3 py-1 mt-2 text-lg transition">+</button>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex flex-col items-center shadow hover:shadow-lg transition-transform transform hover:scale-105 duration-300">
          <img src="img/lumpia.jpg" alt="Pork Bistek" class="w-28 h-28 object-contain mb-3 rounded">
          <h4 class="text-base font-medium text-gray-900">Pork Bistek</h4>
          <p class="text-sm text-gray-500 text-center">Crispy, golden perfection wrapped around savory seasoned meat</p>
          <div class="text-green-600 font-bold mt-2">₱105</div>
          <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white rounded-full px-3 py-1 mt-2 text-lg transition">+</button>
        </div>
      </div>

      <h2 id="group-meals" class="text-xl font-semibold mb-6 text-gray-900 mt-12">Group Meals</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Example Product Card -->
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex flex-col items-center shadow hover:shadow-lg transition-transform transform hover:scale-105 duration-300">
          <img src="img/lumpia.jpg" alt="Lumpia" class="w-28 h-28 object-contain mb-3 rounded">
          <h4 class="text-base font-medium text-gray-900">Barkada Bundle</h4>
          <p class="text-sm text-gray-500 text-center">(Good for 4–5 persons)</p>
          <div class="text-green-600 font-bold mt-2">₱105</div>
          <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white rounded-full px-3 py-1 mt-2 text-lg transition">+</button>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex flex-col items-center shadow hover:shadow-lg transition-transform transform hover:scale-105 duration-300">
          <img src="img/lumpia.jpg" alt="Lumpia" class="w-28 h-28 object-contain mb-3 rounded">
          <h4 class="text-base font-medium text-gray-900">Pamilya Set A</h4>
          <p class="text-sm text-gray-500 text-center">(Good for 6–8 persons)</p>
          <div class="text-green-600 font-bold mt-2">₱105</div>
          <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white rounded-full px-3 py-1 mt-2 text-lg transition">+</button>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex flex-col items-center shadow hover:shadow-lg transition-transform transform hover:scale-105 duration-300">
          <img src="img/lumpia.jpg" alt="Lumpia" class="w-28 h-28 object-contain mb-3 rounded">
          <h4 class="text-base font-medium text-gray-900">Fiesta Meal Package</h4>
          <p class="text-sm text-gray-500 text-center">(Good for 10 persons)</p>
          <div class="text-green-600 font-bold mt-2">₱105</div>
          <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white rounded-full px-3 py-1 mt-2 text-lg transition">+</button>
        </div>
      </div>

      <h2 id="pork-dish" class="text-xl font-semibold mb-6 text-gray-900 mt-12">Pork Dish</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Example Product Card -->
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex flex-col items-center shadow hover:shadow-lg transition-transform transform hover:scale-105 duration-300">
          <img src="img/lumpia.jpg" alt="Lumpia" class="w-28 h-28 object-contain mb-3 rounded">
          <h4 class="text-base font-medium text-gray-900">Pork BBQ</h4>
          <p class="text-sm text-gray-500 text-center">Crispy, golden perfection wrapped around savory seasoned meat</p>
          <div class="text-green-600 font-bold mt-2">₱105</div>
          <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white rounded-full px-3 py-1 mt-2 text-lg transition">+</button>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex flex-col items-center shadow hover:shadow-lg transition-transform transform hover:scale-105 duration-300">
          <img src="img/lumpia.jpg" alt="Lumpia" class="w-28 h-28 object-contain mb-3 rounded">
          <h4 class="text-base font-medium text-gray-900">Pork Bistek</h4>
          <p class="text-sm text-gray-500 text-center">Crispy, golden perfection wrapped around savory seasoned meat</p>
          <div class="text-green-600 font-bold mt-2">₱105</div>
          <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white rounded-full px-3 py-1 mt-2 text-lg transition">+</button>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex flex-col items-center shadow hover:shadow-lg transition-transform transform hover:scale-105 duration-300">
          <img src="img/lumpia.jpg" alt="Lumpia" class="w-28 h-28 object-contain mb-3 rounded">
          <h4 class="text-base font-medium text-gray-900">Adobo</h4>
          <p class="text-sm text-gray-500 text-center">Crispy, golden perfection wrapped around savory seasoned meat</p>
          <div class="text-green-600 font-bold mt-2">₱105</div>
          <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white rounded-full px-3 py-1 mt-2 text-lg transition">+</button>
        </div>
      </div>

      <h2 id="noodles" class="text-xl font-semibold mb-6 text-gray-900 mt-12">Noodles</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Example Product Card -->
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex flex-col items-center shadow hover:shadow-lg transition-transform transform hover:scale-105 duration-300">
          <img src="img/lumpia.jpg" alt="Lumpia" class="w-28 h-28 object-contain mb-3 rounded">
          <h4 class="text-base font-medium text-gray-900">Pancit Bihon</h4>
          <p class="text-sm text-gray-500 text-center">Crispy, golden perfection wrapped around savory seasoned meat</p>
          <div class="text-green-600 font-bold mt-2">₱105</div>
          <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white rounded-full px-3 py-1 mt-2 text-lg transition">+</button>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex flex-col items-center shadow hover:shadow-lg transition-transform transform hover:scale-105 duration-300">
          <img src="img/lumpia.jpg" alt="Lumpia" class="w-28 h-28 object-contain mb-3 rounded">
          <h4 class="text-base font-medium text-gray-900">Bam-I</h4>
          <p class="text-sm text-gray-500 text-center">Crispy, golden perfection wrapped around savory seasoned meat</p>
          <div class="text-green-600 font-bold mt-2">₱105</div>
          <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white rounded-full px-3 py-1 mt-2 text-lg transition">+</button>
        </div>
      </div>

      <h2 id="rice-meals" class="text-xl font-semibold mb-6 text-gray-900 mt-12">Rice Meals</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Example Product Card -->
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex flex-col items-center shadow hover:shadow-lg transition-transform transform hover:scale-105 duration-300">
          <img src="img/lumpia.jpg" alt="Lumpia" class="w-28 h-28 object-contain mb-3 rounded">
          <h4 class="text-base font-medium text-gray-900">Fried Chicken w/ Rice</h4>
          <p class="text-sm text-gray-500 text-center">Crispy, golden perfection wrapped around savory seasoned meat</p>
          <div class="text-green-600 font-bold mt-2">₱105</div>
          <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white rounded-full px-3 py-1 mt-2 text-lg transition">+</button>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex flex-col items-center shadow hover:shadow-lg transition-transform transform hover:scale-105 duration-300">
          <img src="img/lumpia.jpg" alt="Lumpia" class="w-28 h-28 object-contain mb-3 rounded">
          <h4 class="text-base font-medium text-gray-900">Bicol Express</h4>
          <p class="text-sm text-gray-500 text-center">Crispy, golden perfection wrapped around savory seasoned meat</p>
          <div class="text-green-600 font-bold mt-2">₱105</div>
          <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white rounded-full px-3 py-1 mt-2 text-lg transition">+</button>
        </div>
      </div>

      <h2 id="breakfast-meals" class="text-xl font-semibold mb-6 text-gray-900 mt-12">Breakfast Meals</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Example Product Card -->
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex flex-col items-center shadow hover:shadow-lg transition-transform transform hover:scale-105 duration-300">
          <img src="img/lumpia.jpg" alt="Lumpia" class="w-28 h-28 object-contain mb-3 rounded">
          <h4 class="text-base font-medium text-gray-900">Dinuguan</h4>
          <p class="text-sm text-gray-500 text-center">Crispy, golden perfection wrapped around savory seasoned meat</p>
          <div class="text-green-600 font-bold mt-2">₱105</div>
          <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white rounded-full px-3 py-1 mt-2 text-lg transition">+</button>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex flex-col items-center shadow hover:shadow-lg transition-transform transform hover:scale-105 duration-300">
          <img src="img/lumpia.jpg" alt="Lumpia" class="w-28 h-28 object-contain mb-3 rounded">
          <h4 class="text-base font-medium text-gray-900">4-pc. Lumpia Shanghai</h4>
          <p class="text-sm text-gray-500 text-center">Crispy, golden perfection wrapped around savory seasoned meat</p>
          <div class="text-green-600 font-bold mt-2">₱105</div>
          <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white rounded-full px-3 py-1 mt-2 text-lg transition">+</button>
        </div>
      </div>
      
      
    </main>

    <!-- Basket -->
    <aside class="w-64 bg-gray-100 border-l border-gray-300 p-6 flex flex-col justify-between relative h-[calc(100vh-4rem)]">
      <h3 class="text-center text-gray-600 text-lg mb-2">Your Basket is Empty</h3>
      <p class="text-sm text-center text-gray-500">Start ordering, we have what you need!</p>
      <div class="text-5xl opacity-20 my-6">🧺</div>
      <div class="flex justify-between w-full font-semibold mb-4">
        <span>Total:</span>
        <span>₱0</span>
      </div>
      <button id="checkout-button" class="bg-red-500 hover:bg-red-600 text-white w-full py-2 rounded disabled:opacity-50 transition" disabled>Checkout</button>
    </aside>
  </div>

  <!-- Slide-in Profile Sidebar -->
<aside id="profile-sidebar"
  class="fixed top-0 right-0 h-full w-72 bg-white shadow-lg transform translate-x-full transition-transform duration-300 z-50 p-6 flex flex-col justify-between">
  
  <!-- Top Content -->
  <div>
    <!-- Profile Header -->
<div class="flex items-center gap-4 mb-8 border-b border-gray-200 pb-4">
  <img src="img/user.jpg" alt="Profile" class="w-12 h-12 rounded-full object-cover">
  <div>
    <h4 class="font-semibold text-gray-800">John Doe</h4>
    <p class="text-sm text-gray-500 italic">Customer</p>
  </div>
</div>

<!-- Address -->
<div class="mb-6">
  <h5 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Address</h5>
  <p class="text-sm text-gray-700 leading-relaxed bg-gray-50 p-3 rounded-lg border border-gray-200">
    123 Food St., Cebu City, PH
  </p>
</div>

<!-- Settings -->
<div class="mb-6">
  <h5 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Settings</h5>
  <ul class="space-y-2 text-sm text-gray-700">
    <li>
      <a href="#" class="flex items-center justify-between px-3 py-2 rounded-md hover:bg-red-50 hover:text-red-500 transition">
        Edit Profile
        <span class="text-xs text-gray-400">›</span>
      </a>
    </li>
    <li>
      <a href="#" class="flex items-center justify-between px-3 py-2 rounded-md hover:bg-red-50 hover:text-red-500 transition">
        Notification Settings
        <span class="text-xs text-gray-400">›</span>
      </a>
    </li>
    <li>
      <a href="#" class="flex items-center justify-between px-3 py-2 rounded-md hover:bg-red-50 hover:text-red-500 transition">
        Order History
        <span class="text-xs text-gray-400">›</span>
      </a>
    </li>
  </ul>
</div>


  <!-- Logout -->
  <button class="w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded font-semibold text-sm">Logout</button>
</aside>

<!-- Clear Basket Modal -->
<div id="clearModal" class="fixed inset-0 z-50 bg-black bg-opacity-30 flex items-center justify-center hidden">
  <div class="bg-white rounded-lg shadow-lg p-6 w-80 animate-modalFadeIn">
    <h2 class="text-lg font-semibold text-gray-800 mb-3">Clear Basket?</h2>
    <p class="text-sm text-gray-600 mb-4">Are you sure you want to remove all items from your basket?</p>
    <div class="flex justify-end gap-3">
      <button id="cancelClear" class="px-4 py-1.5 text-sm rounded bg-gray-200 hover:bg-gray-300 text-gray-800">Cancel</button>
      <button id="confirmClear" class="px-4 py-1.5 text-sm rounded bg-red-500 hover:bg-red-600 text-white">Yes, Clear</button>
    </div>
  </div>
</div>

<div id="checkoutModal" class="fixed inset-0 z-50 bg-black bg-opacity-30 flex items-center justify-center hidden">
  <div class="bg-white rounded-lg shadow-lg p-6 w-80 animate-modalFadeIn">
    <h2 class="text-lg font-semibold text-gray-800 mb-3 text-center">Order Placed Successfully</h2>
    <div class="flex justify-center gap-3">
      <button id="closeModal" class="px-4 py-1.5 text-sm rounded bg-red-500 hover:bg-red-600 text-white">Close</button>
    </div>
  </div>
</div>

  <script>
    const currentUserId = <?= json_encode($_SESSION['customer_id']); ?>;
  </script>

  <script src="orderPageFunction.js" defer></script>
</body>
</html>
