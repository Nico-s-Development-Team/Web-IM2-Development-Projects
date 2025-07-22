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
      <input
        type="text"
        placeholder="Search dishes or meals..."
        class="w-full max-w-md px-4 py-1.5 rounded-full border border-gray-300 shadow-sm text-sm focus:outline-none focus:ring-2 focus:ring-red-400"
      />
    </div>

          <!-- Recommended Menu Section -->
      <section class="mb-10">
        <h2 class="text-xl font-semibold mb-4 text-gray-900">🔥 Recommended for You</h2>
        
        <div class="overflow-x-auto">
          <div class="flex space-x-4">
            <!-- Recommended Item Card -->
            <div class="min-w-[220px] bg-white border border-gray-200 rounded-xl p-4 flex-shrink-0 shadow hover:shadow-md transition-transform transform hover:scale-105 duration-300">
              <img src="img/lumpia.jpg" alt="Special Burger" class="w-full h-32 object-cover rounded mb-3">
              <h4 class="text-base font-semibold text-gray-900">Special Burger</h4>
              <p class="text-sm text-gray-500">Juicy, smoky, mouth-watering bite in every layer.</p>
              <div class="text-green-600 font-bold mt-2">₱129</div>
              <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white rounded px-3 py-1 mt-2 text-sm transition">Add +</button>
            </div>

            <!-- Another Recommended Item -->
            <div class="min-w-[220px] bg-white border border-gray-200 rounded-xl p-4 flex-shrink-0 shadow hover:shadow-md transition-transform transform hover:scale-105 duration-300">
              <img src="img/lumpia.jpg" alt="Cheesy Burger" class="w-full h-32 object-cover rounded mb-3">
              <h4 class="text-base font-semibold text-gray-900">Cheesy Burger</h4>
              <p class="text-sm text-gray-500">Overflowing cheese and premium patty perfection.</p>
              <div class="text-green-600 font-bold mt-2">₱115</div>
              <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white rounded px-3 py-1 mt-2 text-sm transition">Add +</button>
            </div>

            <div class="min-w-[220px] bg-white border border-gray-200 rounded-xl p-4 flex-shrink-0 shadow hover:shadow-md transition-transform transform hover:scale-105 duration-300">
              <img src="img/lumpia.jpg" alt="Cheesy Burger" class="w-full h-32 object-cover rounded mb-3">
              <h4 class="text-base font-semibold text-gray-900">Cheesy Burger</h4>
              <p class="text-sm text-gray-500">Overflowing cheese and premium patty perfection.</p>
              <div class="text-green-600 font-bold mt-2">₱115</div>
              <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white rounded px-3 py-1 mt-2 text-sm transition">Add +</button>
            </div>

            <div class="min-w-[220px] bg-white border border-gray-200 rounded-xl p-4 flex-shrink-0 shadow hover:shadow-md transition-transform transform hover:scale-105 duration-300">
              <img src="img/lumpia.jpg" alt="Cheesy Burger" class="w-full h-32 object-cover rounded mb-3">
              <h4 class="text-base font-semibold text-gray-900">Cheesy Burger</h4>
              <p class="text-sm text-gray-500">Overflowing cheese and premium patty perfection.</p>
              <div class="text-green-600 font-bold mt-2">₱115</div>
              <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white rounded px-3 py-1 mt-2 text-sm transition">Add +</button>
            </div>

            <div class="min-w-[220px] bg-white border border-gray-200 rounded-xl p-4 flex-shrink-0 shadow hover:shadow-md transition-transform transform hover:scale-105 duration-300">
              <img src="img/lumpia.jpg" alt="Cheesy Burger" class="w-full h-32 object-cover rounded mb-3">
              <h4 class="text-base font-semibold text-gray-900">Cheesy Burger</h4>
              <p class="text-sm text-gray-500">Overflowing cheese and premium patty perfection.</p>
              <div class="text-green-600 font-bold mt-2">₱115</div>
              <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white rounded px-3 py-1 mt-2 text-sm transition">Add +</button>
            </div>

            <!-- Add more cards as needed -->
          </div>
        </div>
      </section>

      <hr class="my-8 border-t-2 border-gray-300">

      <h2 id="todays-offer" class="text-xl font-semibold mb-6 text-gray-900">Today's Offer</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Example Product Card -->
        <div class="bg-white rounded-2xl border border-gray-300 p-4 flex flex-col items-center shadow-sm hover:shadow-md transition duration-300">
          <img src="img/Menu-2/Chicken_Rice_Bowl_2.jpg" alt="Fried Chicken" class="w-full h-40 object-cover rounded-xl mb-3">
          <div class="text-center w-full">
            <h4 class="text-lg font-bold text-gray-900 mb-1">Fried Chicken</h4>
            <p class="text-xs text-gray-500 leading-tight mb-3">Crispy outside, juicy inside—chicken done right!</p>
            <div class="flex items-center justify-between w-full mt-auto">
              <div class="text-green-600 font-bold text-lg">₱105</div>
                <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white text-sm px-4 py-1.5 rounded-full transition">
                Add +
              </button>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-300 p-4 flex flex-col items-center shadow-sm hover:shadow-md transition duration-300">
          <img src="img/lumpia.jpg" alt="Lumpia Shanghai" class="w-full h-40 object-cover rounded-xl mb-3">
          <div class="text-center w-full">
            <h4 class="text-lg font-bold text-gray-900 mb-1">Lumpia Shanghai</h4>
            <p class="text-xs text-gray-500 leading-tight mb-3">Crispy, golden perfection wrapped around savory seasoned meat</p>
            <div class="flex items-center justify-between w-full mt-auto">
              <div class="text-green-600 font-bold text-lg">₱105</div>
                <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white text-sm px-4 py-1.5 rounded-full transition">
                Add +
              </button>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-300 p-4 flex flex-col items-center shadow-sm hover:shadow-md transition duration-300">
          <img src="img/Menu-2/Pork_BBQ.jpg" alt="Pork BBQ" class="w-full h-40 object-cover rounded-xl mb-3">
          <div class="text-center w-full">
            <h4 class="text-lg font-bold text-gray-900 mb-1">Pork BBQ</h4>
            <p class="text-xs text-gray-500 leading-tight mb-3">Smoky, sweet, and tender—skewers that steal the show!</p>
            <div class="flex items-center justify-between w-full mt-auto">
              <div class="text-green-600 font-bold text-lg">₱105</div>
                <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white text-sm px-4 py-1.5 rounded-full transition">
                Add +
              </button>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-300 p-4 flex flex-col items-center shadow-sm hover:shadow-md transition duration-300">
          <img src="img/Menu-2/Pork_Bistek_3.jpg" alt="Pork Bistek" class="w-full h-40 object-cover rounded-xl mb-3">
          <div class="text-center w-full">
            <h4 class="text-lg font-bold text-gray-900 mb-1">Pork Bistek</h4>
            <p class="text-xs text-gray-500 leading-tight mb-3">Tender pork slices in tangy soy-calamansi sauce</p>
            <div class="flex items-center justify-between w-full mt-auto">
              <div class="text-green-600 font-bold text-lg">₱105</div>
                <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white text-sm px-4 py-1.5 rounded-full transition">
                Add +
              </button>
            </div>
          </div>
        </div>
      </div>
      <hr class="my-8 border-t-2 border-gray-300">
      <h2 id="group-meals" class="text-xl font-semibold mb-6 text-gray-900 mt-12">Group Meals</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Example Product Card -->
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex flex-col items-center shadow hover:shadow-lg transition-transform transform hover:scale-105 duration-300">
          <img src="img/Menu-2/barkada_bundle.jpg" alt="barkada" class="w-28 h-28 object-cover mb-3 rounded">
          <h4 class="text-base font-medium text-gray-900">Barkada Bundle</h4>
          <p class="text-sm text-gray-500 text-center">(Good for 4–5 persons)</p>
          <div class="text-green-600 font-bold mt-2">₱105</div>
          <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white rounded px-3 py-1 mt-2 text-lg transition">Add +</button>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex flex-col items-center shadow hover:shadow-lg transition-transform transform hover:scale-105 duration-300">
          <img src="img/Menu-2/family_set.jpg" alt="Lumpia" class="w-28 h-28 object-cover mb-3 rounded">
          <h4 class="text-base font-medium text-gray-900">Pamilya Set A</h4>
          <p class="text-sm text-gray-500 text-center">(Good for 6–8 persons)</p>
          <div class="text-green-600 font-bold mt-2">₱105</div>
          <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white rounded px-3 py-1 mt-2 text-lg transition">Add +</button>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex flex-col items-center shadow hover:shadow-lg transition-transform transform hover:scale-105 duration-300">
          <img src="img/Menu-2/fiesta_meal.jpg" alt="Lumpia" class="w-28 h-28 object-cover mb-3 rounded">
          <h4 class="text-base font-medium text-gray-900">Fiesta Meal Package</h4>
          <p class="text-sm text-gray-500 text-center">(Good for 10 persons)</p>
          <div class="text-green-600 font-bold mt-2">₱105</div>
          <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white rounded px-3 py-1 mt-2 text-lg transition">Add +</button>
        </div>
      </div>
      <hr class="my-8 border-t-2 border-gray-300">
      <h2 id="pork-dish" class="text-xl font-semibold mb-6 text-gray-900 mt-12">Pork Dish</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Example Product Card -->
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex flex-col items-center shadow hover:shadow-lg transition-transform transform hover:scale-105 duration-300">
          <img src="img/Menu-2/Pork_BBQ.jpg" alt="Lumpia" class="w-28 h-28 object-cover mb-3 rounded">
          <h4 class="text-base font-medium text-gray-900">Pork BBQ</h4>
          <p class="text-sm text-gray-500 text-center">Smoky, sweet, and tender—skewers that steal the show!</p>
          <div class="text-green-600 font-bold mt-2">₱105</div>
          <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white rounded px-3 py-1 mt-2 text-lg transition">Add +</button>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex flex-col items-center shadow hover:shadow-lg transition-transform transform hover:scale-105 duration-300">
          <img src="img/Menu-2/Pork_Bistek_3.jpg" alt="Lumpia" class="w-28 h-28 object-cover mb-3 rounded">
          <h4 class="text-base font-medium text-gray-900">Pork Bistek</h4>
          <p class="text-sm text-gray-500 text-center">Tender pork slices in tangy soy-calamansi sauce</p>
          <div class="text-green-600 font-bold mt-2">₱105</div>
          <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white rounded px-3 py-1 mt-2 text-lg transition">Add +</button>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex flex-col items-center shadow hover:shadow-lg transition-transform transform hover:scale-105 duration-300">
          <img src="img/lumpia.jpg" alt="Lumpia" class="w-28 h-28 object-cover mb-3 rounded">
          <h4 class="text-base font-medium text-gray-900">Adobo</h4>
          <p class="text-sm text-gray-500 text-center">Crispy, golden perfection wrapped around savory seasoned meat</p>
          <div class="text-green-600 font-bold mt-2">₱105</div>
          <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white rounded px-3 py-1 mt-2 text-lg transition">Add +</button>
        </div>
      </div>
      <hr class="my-8 border-t-2 border-gray-300">
      <h2 id="noodles" class="text-xl font-semibold mb-6 text-gray-900 mt-12">Noodles</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Example Product Card -->
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex flex-col items-center shadow hover:shadow-lg transition-transform transform hover:scale-105 duration-300">
          <img src="img/Menu-2/Pancit_Bihon.jpg" alt="Lumpia" class="w-28 h-28 object-cover mb-3 rounded">
          <h4 class="text-base font-medium text-gray-900">Pancit Bihon</h4>
          <p class="text-sm text-gray-500 text-center">Long life, big flavor—celebrate with every noodle forkful!</p>
          <div class="text-green-600 font-bold mt-2">₱105</div>
          <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white rounded px-3 py-1 mt-2 text-lg transition">Add +</button>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex flex-col items-center shadow hover:shadow-lg transition-transform transform hover:scale-105 duration-300">
          <img src="img/Menu-2/Bam-I_3.jpg" alt="Bam-I" class="w-28 h-28 object-cover mb-3 rounded">
          <h4 class="text-base font-medium text-gray-900">Bam-I</h4>
          <p class="text-sm text-gray-500 text-center">Two noodles, one unforgettable Filipino-Chinese fusion!</p>
          <div class="text-green-600 font-bold mt-2">₱105</div>
          <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white rounded px-3 py-1 mt-2 text-lg transition">Add +</button>
        </div>
      </div>
      <hr class="my-8 border-t-2 border-gray-300">
      <h2 id="rice-meals" class="text-xl font-semibold mb-6 text-gray-900 mt-12">Rice Meals</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Example Product Card -->
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex flex-col items-center shadow hover:shadow-lg transition-transform transform hover:scale-105 duration-300">
          <img src="img/Menu-2/Chicken_Rice_Bowl_1.jpg" alt="Lumpia" class="w-28 h-28 object-cover mb-3 rounded">
          <h4 class="text-base font-medium text-gray-900">Fried Chicken w/ Rice</h4>
          <p class="text-sm text-gray-500 text-center">Crispy on the outside, juicy on the inside—chicken done right!</p>
          <div class="text-green-600 font-bold mt-2">₱105</div>
          <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white rounded px-3 py-1 mt-2 text-lg transition">Add +</button>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex flex-col items-center shadow hover:shadow-lg transition-transform transform hover:scale-105 duration-300">
          <img src="img/Menu-2/Bicol_Express.jpg" alt="Lumpia" class="w-28 h-28 object-cover mb-3 rounded">
          <h4 class="text-base font-medium text-gray-900">Bicol Express</h4>
          <p class="text-sm text-gray-500 text-center">Creamy, spicy, and addictively good—Bicolano heat in every bite!</p>
          <div class="text-green-600 font-bold mt-2">₱105</div>
          <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white rounded px-3 py-1 mt-2 text-lg transition">Add +</button>
        </div>
      </div>
      <hr class="my-8 border-t-2 border-gray-300">
      <h2 id="breakfast-meals" class="text-xl font-semibold mb-6 text-gray-900 mt-12">Breakfast Meals</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Example Product Card -->
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex flex-col items-center shadow hover:shadow-lg transition-transform transform hover:scale-105 duration-300">
          <img src="img/lumpia.jpg" alt="Lumpia" class="w-28 h-28 object-cover mb-3 rounded">
          <h4 class="text-base font-medium text-gray-900">Dinuguan</h4>
          <p class="text-sm text-gray-500 text-center">Crispy, golden perfection wrapped around savory seasoned meat</p>
          <div class="text-green-600 font-bold mt-2">₱105</div>
          <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white rounded px-3 py-1 mt-2 text-lg transition">Add +</button>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex flex-col items-center shadow hover:shadow-lg transition-transform transform hover:scale-105 duration-300">
          <img src="img/lumpia.jpg" alt="Lumpia" class="w-28 h-28 object-cover mb-3 rounded">
          <h4 class="text-base font-medium text-gray-900">4-pc. Lumpia Shanghai</h4>
          <p class="text-sm text-gray-500 text-center">Crispy, golden perfection wrapped around savory seasoned meat</p>
          <div class="text-green-600 font-bold mt-2">₱105</div>
          <button class="add-to-cart bg-red-500 hover:bg-red-600 text-white rounded px-3 py-1 mt-2 text-lg transition">Add +</button>
        </div>
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
      <a href="newProfile.html" class="flex items-center justify-between px-3 py-2 rounded-md hover:bg-red-50 hover:text-red-500 transition">
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
</body>
</html>