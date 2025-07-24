document.addEventListener("DOMContentLoaded", () => {
  fetch("fetch_recommended.php")
    .then(res => res.json())
    .then(data => {
      const container = document.getElementById("recommendedContainer");
      container.innerHTML = ""; // Clear previous content

      data.forEach(item => {
        const card = document.createElement("div");
        card.className = "min-w-[380px] bg-white border border-gray-200 rounded-xl p-4 flex-shrink-0 shadow duration-300";

        card.innerHTML = `
          <img src="${item.Image_URL}" alt="${item.Item_Name}" class="w-full h-32 object-cover rounded mb-3">
          <h4 class="text-base font-semibold text-gray-900">${item.Item_Name}</h4>
          <p class="text-sm text-gray-500">A special treat you’ll love.</p>
          <div class="text-green-600 font-bold mt-2">₱${parseFloat(item.Price).toFixed(2)}</div>
          <button 
            class="add-to-cart bg-red-500 hover:bg-red-600 text-white rounded px-3 py-1 mt-2 text-sm transition"
            data-id="${item.Item_ID}" 
            data-name="${item.Item_Name}" 
            data-price="${item.Price}">
            Add +
          </button>
        `;

        container.appendChild(card);
      });
    })
    .catch(err => console.error("Failed to load recommended items:", err));
});
