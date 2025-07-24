<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

// Access control: redirect if not logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: home.html?error=wrong_password");
    exit;
}

require 'db_conn.php';

$customerName = "Customer";
$customerAddress = "Address not found";

$customerId = $_SESSION['customer_id'];

$stmt = $conn->prepare("SELECT Customer_FirstName, Customer_LastName, Customer_Address, Customer_Email FROM Customer_T WHERE Customer_ID = ?");
$stmt->bind_param("i", $customerId);
$stmt->execute();
$stmt->bind_result($firstName, $lastName, $address, $email);

if ($stmt->fetch()) {
    $customerName = $firstName;
    $customerAddress = $address;
    $_SESSION['customer_email'] = $email; 
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="0" />
  <title>Nico's Food Delivery - Order</title>
  <link rel="stylesheet" href="orderPageStyle.css">
  <script src="refresh_page.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Open+Sans:wght@400;500&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Open Sans', sans-serif;
      background: linear-gradient(to right, #f87171, #fcd34d);
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
    <script src="https://unpkg.com/lucide@latest"></script>

    <div class="flex px-6 gap-4">
      <aside class="w-60 bg-gradient-to-b from-yellow-100 to-yellow-300 border-r border-gray-300 p-5 flex-shrink-0 mt-6 mb-6 rounded-xl shadow-lg">
        <h3 class="text-red-500 mb-6 text-xl font-bold tracking-tight">🍱 MENU DEALS</h3>
        <nav>
          <ul id="sidebar-menu" class="space-y-2 text-[15px] font-medium text-gray-800">
            <li>
              <a href="#recommended" class="flex items-center gap-2 px-4 py-2 rounded-md hover:bg-yellow-200 hover:text-red-600 border-l-4 border-transparent hover:border-red-500 transition-all">
                <i data-lucide="thumbs-up" class="w-4 h-4"></i>
                Recommended
              </a>
            </li>
            <li>
              <a href="#todays-offer" class="flex items-center gap-2 px-4 py-2 rounded-md hover:bg-yellow-200 hover:text-red-600 border-l-4 border-transparent hover:border-red-500 transition-all">
                <i data-lucide="star" class="w-4 h-4"></i>
                Today's Offer
              </a>
            </li>
            <li>
              <a href="#group-meals" class="flex items-center gap-2 px-4 py-2 rounded-md hover:bg-yellow-200 hover:text-red-600 border-l-4 border-transparent hover:border-red-500 transition-all">
                <i data-lucide="users" class="w-4 h-4"></i>
                Group Meals
              </a>
            </li>
            <li>
              <a href="#pork-dish" class="flex items-center gap-2 px-4 py-2 rounded-md hover:bg-yellow-200 hover:text-red-600 border-l-4 border-transparent hover:border-red-500 transition-all">
                <i data-lucide="piggy-bank" class="w-4 h-4"></i>
                Pork Dish
              </a>
            </li>
            <li>
              <a href="#noodles" class="flex items-center gap-2 px-4 py-2 rounded-md hover:bg-yellow-200 hover:text-red-600 border-l-4 border-transparent hover:border-red-500 transition-all">
                <i data-lucide="cup-soda" class="w-4 h-4"></i>
                Noodles
              </a>
            </li>
            <li>
              <a href="#rice-meals" class="flex items-center gap-2 px-4 py-2 rounded-md hover:bg-yellow-200 hover:text-red-600 border-l-4 border-transparent hover:border-red-500 transition-all">
                <i data-lucide="drumstick" class="w-4 h-4"></i>
                Rice Meals
              </a>
            </li>
            <li>
              <a href="#breakfast-meals" class="flex items-center gap-2 px-4 py-2 rounded-md hover:bg-yellow-200 hover:text-red-600 border-l-4 border-transparent hover:border-red-500 transition-all">
                <i data-lucide="egg" class="w-4 h-4"></i>
                Breakfast Meals
              </a>
            </li>
          </ul>
        </nav>
      </aside>
    </div>
    

    <!-- Main Content -->
    <main class="flex-1 max-h-[calc(100vh-4rem)] overflow-y-auto p-6">
      <!-- Search Bar -->
    <!-- Search Bar -->
    <div class="w-full flex justify-center mb-6">
  <p class="text-2xl md:text-3xl font-extrabold bg-gradient-to-r from-amber-500 via-yellow-400 to-amber-500 bg-clip-text text-transparent drop-shadow-md tracking-wide animate-pulse">
    BROWSE SELECTION OF FOODS
  </p>
</div>


          <!-- Recommended Menu Section -->
      <section class="mb-10">
        <div class="flex items-center gap-3 mb-6 mt-12">
          <div class="h-10 w-1 bg-red-500 rounded-full"></div>
          <h2 id="recommended" class="text-white text-xl font-bold px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 rounded-lg shadow-md">
            Recommended for You
          </h2>
        </div>

        
        <div class="overflow-x-auto">
          <div id="recommendedContainer" class="flex space-x-4">
            <!-- Add more cards as needed -->

          </div>
        </div>
      </section>

      <hr class="my-8 border-t-2 border-gray-300">

      <div class="flex items-center gap-3 mb-6">
        <div class="h-10 w-1 bg-red-500 rounded-full"></div>
        <h2 id="todays-offer" class="text-white text-xl font-bold px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 rounded-lg shadow-md">
          Today's Offer
        </h2>
      </div>
      <div id="todays-offer-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Example Product Card -->
        
      </div>
      <hr class="my-8 border-t-2 border-gray-300">
      <div class="flex items-center gap-3 mb-6 mt-12">
        <div class="h-10 w-1 bg-red-500 rounded-full"></div>
        <h2 id="group-meals" class="text-white text-xl font-bold px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 rounded-lg shadow-md">
          Group Meals
        </h2>
      </div>
      <div id="group-meals-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Example Product Card -->
    
      </div>
      <hr class="my-8 border-t-2 border-gray-300">
      <div class="flex items-center gap-3 mb-6 mt-12">
        <div class="h-10 w-1 bg-red-500 rounded-full"></div>
        <h2 id="pork-dish" class="text-white text-xl font-bold px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 rounded-lg shadow-md">
          Pork Dish
        </h2>
      </div>

      <div id="pork-dish-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Example Product Card -->
        
      </div>
      <hr class="my-8 border-t-2 border-gray-300">
      <div class="flex items-center gap-3 mb-6 mt-12">
        <div class="h-10 w-1 bg-red-500 rounded-full"></div>
        <h2 id="noodles" class="text-white text-xl font-bold px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 rounded-lg shadow-md">
          Noodles
        </h2>
      </div>

      <div id="noodles-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Example Product Card -->
        
      </div>
      <hr class="my-8 border-t-2 border-gray-300">
      <div class="flex items-center gap-3 mb-6 mt-12">
        <div class="h-10 w-1 bg-red-500 rounded-full"></div>
        <h2 id="rice-meals" class="text-white text-xl font-bold px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 rounded-lg shadow-md">
          Rice Meals
        </h2>
      </div>

      <div id="rice-meals-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Example Product Card -->
        
      </div>
      <hr class="my-8 border-t-2 border-gray-300">
      <div class="flex items-center gap-3 mb-6 mt-12">
        <div class="h-10 w-1 bg-red-500 rounded-full"></div>
        <h2 id="breakfast-meals" class="text-white text-xl font-bold px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 rounded-lg shadow-md">
          Breakfast Meals
        </h2>
      </div>

      <div id="breakfast-meals-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Example Product Card -->
        
      </div>
      
      
    </main>

   <!-- Basket -->
<aside class="w-72 bg-gradient-to-b from-red-50 to-white border-l border-red-200 p-6 flex flex-col justify-between relative h-[calc(100vh-4rem)] shadow-inner">
  <div>
    <h3 class="text-center text-red-600 text-xl font-bold mb-1">Your Basket</h3>
    <p class="text-sm text-center text-gray-500 mb-6">Start adding delicious food to your cart!</p>

    <!-- Basket Items Placeholder -->
    <div id="basket-items" class="flex-1 overflow-y-auto space-y-4 text-gray-800">
      <!-- Example placeholder item -->
      <div class="flex justify-between items-center border-b pb-2">
        <div>
          <p class="font-medium">No items yet</p>
          <p class="text-xs text-gray-400">Your cart is waiting...</p>
        </div>
        <div class="text-sm text-gray-500">₱0</div>
      </div>
    </div>

    <!-- Basket Illustration -->
    <div id="basket-icon" class="text-6xl opacity-10 text-center my-6 pointer-events-none select-none">
      🧺
    </div>
  </div>

  <!-- Total & Checkout -->
  <div class="sticky bottom-0 bg-white py-4 mt-auto border-t border-gray-200">
    <div class="flex justify-between font-bold text-gray-700 mb-3 text-lg px-1">
      <span>Total:</span>
      <span id="basket-total">₱0</span>
    </div>
    <button id="checkout-button"
      class="bg-red-500 hover:bg-red-600 text-white w-full py-2 rounded-lg font-medium disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
      disabled>
      Checkout
    </button>
  </div>
</aside>


  <!-- Slide-in Profile Sidebar -->
<aside id="profile-sidebar"
  class="fixed top-0 right-0 h-full w-72 bg-white shadow-lg transform translate-x-full transition-transform duration-300 z-50 p-6 flex flex-col justify-between">
  
  <!-- Top Content -->
  <div>
    <!-- Profile Header -->
<div class="flex items-center gap-4 mb-8 border-b border-gray-200 pb-4">
  <img src="img/user.jpg" alt="Profile" class="w-12 h-12 rounded-full object-cover">
  <div class="px-4 mt-4">
    <h3 class="text-xl font-semibold text-gray-800"><?= htmlspecialchars($customerName) ?></h3>
    <p class="text-sm text-gray-600"><?= htmlspecialchars($_SESSION['customer_email']) ?></p>
  </div>
</div>

<!-- Address -->
<div class="mb-6">
  <h5 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Address</h5>
  <p class="text-sm text-gray-700 leading-relaxed bg-gray-50 p-3 rounded-lg border border-gray-200">
  <?= htmlspecialchars($customerAddress) ?>
</p>
</div>

<!-- Settings -->
<div class="mb-6">
  <h5 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Settings</h5>
  <ul class="space-y-2 text-sm text-gray-700">
    <li>
      <a href="newProfile.php" class="flex items-center justify-between px-3 py-2 rounded-md hover:bg-red-50 hover:text-red-500 transition">
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
      <a href="orderhistory.html" class="flex items-center justify-between px-3 py-2 rounded-md hover:bg-red-50 hover:text-red-500 transition">
        Order History
        <span class="text-xs text-gray-400">›</span>
      </a>
    </li>
  </ul>
</div>


  <!-- Logout -->
  <button id="logoutBtn" class="w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded font-semibold text-sm">Logout</button>
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

<!-- Checkout Modal -->
<div id="checkoutModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-40 flex justify-center items-center px-4">
  <div class="bg-white w-full max-w-xl rounded-lg shadow-lg p-6 relative">
    
    <!-- Step Navigation -->
<div class="flex items-center justify-between mb-6">
  <!-- Step 1 -->
  <div class="flex items-center gap-2">
    <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold bg-gray-300 text-white">1</div>
    <span class="text-sm text-gray-500">Menu</span>
  </div>

  <!-- Line -->
  <div class="flex-1 h-1 mx-2 bg-gray-300 rounded"></div>

  <!-- Step 2 (Current) -->
  <div class="flex items-center gap-2">
    <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold bg-red-500 text-white ring-2 ring-red-300">2</div>
    <span class="text-sm text-red-600 font-medium">Basket</span>
  </div>

  <!-- Line -->
  <div class="flex-1 h-1 mx-2 bg-gray-300 rounded"></div>

  <!-- Step 3 -->
  <div class="flex items-center gap-2">
    <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold bg-gray-300 text-white">3</div>
    <span class="text-sm text-gray-500">Checkout</span>
  </div>
</div>


    <!-- Basket Summary -->
    <div id="checkoutSummary" class="space-y-4 max-h-64 overflow-y-auto border-t pt-4">
      <!-- JS will populate this -->
    </div>

    <!-- Total -->
    <div class="flex justify-between items-center mt-4 border-t pt-4 font-semibold">
      <span>Total:</span>
      <span id="checkoutTotal" class="text-red-500">₱0.00</span>
    </div>

    <!-- Actions -->
    <div class="flex justify-end gap-3 mt-6">
      <button id="cancelCheckout" class="px-4 py-2 text-sm border rounded text-gray-600 hover:text-red-500 hover:border-red-500">Cancel</button>
      <button id="goToReview" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 text-sm">Proceed to Checkout</button>
    </div>
  </div>
</div>


<div id="loading-overlay" class="fixed inset-0 bg-white bg-opacity-70 flex items-center justify-center hidden z-50">
  <div class="text-center">
    <svg class="animate-spin h-10 w-10 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
    </svg>
    <p class="mt-2 text-gray-700 text-sm">Loading...</p>
  </div>
</div>



  <script>
    lucide.createIcons();

    document.getElementById("logoutBtn").addEventListener("click", () => {
    window.location.href = "logout.php";
    });
    
    const currentUserId = <?= json_encode($_SESSION['customer_id'] ?? null); ?>;
  </script>

  

  <script src="orderPageFunction.js" defer></script>
  <script src="fetch_recommended.js" ></script>
  <script src="productLoader.js" ></script>
</body>
</html>