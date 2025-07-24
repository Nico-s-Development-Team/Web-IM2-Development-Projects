document.addEventListener("DOMContentLoaded", function () {
  const categories = {
    "Today's Offer": "todays-offer-container",
    "Group Meals": "group-meals-container",
    "Pork Dish": "pork-dish-container",
    "Noodles": "noodles-container",
    "Rice Meals": "rice-meals-container",
    "Breakfast Meals": "breakfast-meals-container"
  };

  Object.entries(categories).forEach(([categoryName, containerId]) => {
    fetch(`fetch_productList.php?category=${encodeURIComponent(categoryName)}`)
      .then(res => res.json())
      .then(data => {
        const container = document.getElementById(containerId);
        container.innerHTML = "";

        data.forEach(item => {
          const card = document.createElement("div");
          card.className = "w-64 h-[18rem] flex flex-col justify-between item-center shadow-md bg-white rounded-xl overflow-hidden";

          card.innerHTML = `
            <div class="flex justify-center mt-4">
                <img src="${item.Image_URL}" alt="${item.Item_Name}" class="w-full h-28 mb-2 rounded object-cover">
            </div>
            <div class="bg-gradient-to-r from-amber-300 to-yellow-400 text-center py-2 font-semibold">
                ${item.Item_Name}
            </div>
            <div class="px-4 py-2 flex-1 flex flex-col justify-center text-center">
                <p class="text-lg font-bold text-gray-800 mb-1">₱${parseFloat(item.Price).toFixed(2)}</p>
                <p class="text-sm text-gray-500">Stock: ${item.Quantity}</p>
            </div>
            <div class="text-center p-1">
                <button class="add-to-cart bg-amber-400 hover:bg-amber-500 transition-colors px-4 py-1 rounded-full font-medium"
                data-id="${item.Item_ID}"
                data-name="${item.Item_Name}"
                data-price="${item.Price}">
                Add to Cart
                </button>
            </div>
            `;
          container.appendChild(card);
        });
      })
      .catch(err => console.error("Error loading category:", categoryName, err));
  });
});
