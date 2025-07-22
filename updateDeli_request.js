lucide.createIcons();

let editingDeliveryIndex = null;
let deliveries = [];

const deliveryTable = document.getElementById("deliveryTable");
const searchInput = document.getElementById("searchDelivery");
const statusFilter = document.getElementById("statusFilter");

async function loadDeliveries() {
  try {
    console.log("Loading deliveries...");
    const response = await fetch('fetch_deliveries.php?type=active');
    deliveries = await response.json();
    renderDeliveries(searchInput.value, statusFilter.value);
  } catch (error) {
    console.error('Error loading deliveries:', error);
  }
}

function renderDeliveries(filterText = "", filterStatus = "") {
  deliveryTable.innerHTML = "";
  deliveries
    .filter(d =>
      (d.address.toLowerCase().includes(filterText.toLowerCase()) || d.rider.toLowerCase().includes(filterText.toLowerCase())) &&
      (filterStatus === "" || d.status === filterStatus)
    )
    .forEach(d => {
      const row = document.createElement("tr");
      row.innerHTML = `
        <td class="px-4 py-2">${d.orderId}</td>
        <td class="px-4 py-2">${d.address}</td>
        <td class="px-4 py-2">
        ${d.rider === "Unassigned"
            ? '<span class="text-red-500">Unassigned</span>'
            : d.rider}
        </td>
        <td class="px-4 py-2">${d.status}</td>
        <td class="px-4 py-2">${formatDateTime(d.completionTime)}</td>
        <td class="px-4 py-2 text-right space-x-2">
          <button onclick="openDeliveryModal(${deliveries.indexOf(d)})" class="bg-blue-600 text-white px-3 py-1 rounded text-sm">Update</button>
        </td>
      `;
      deliveryTable.appendChild(row);
    });
}

function formatDateTime(isoString) {
  if (!isoString) return '—';
  const date = new Date(isoString);
  return new Intl.DateTimeFormat('en-US', {
    year: 'numeric', month: 'short', day: 'numeric',
    hour: 'numeric', minute: '2-digit',
    hour12: true
  }).format(date);
}


function closeDeliveryModal() {
  const modal = document.getElementById("deliveryModal");
  modal.classList.add("hidden");
  modal.classList.remove("flex");
}

function openHistoryModal() {
  const tableBody = document.getElementById("historyTable");
  tableBody.innerHTML = "";

  deliveries
    .filter(d => ["Delivered", "Failed", "Cancelled"].includes(d.status))
    .forEach((d) => {
      const row = document.createElement("tr");
      row.innerHTML = `
        <td class="px-4 py-2">${d.orderId}</td>
        <td class="px-4 py-2">${d.address}</td>
        <td class="px-4 py-2">${d.rider}</td>
        <td class="px-4 py-2">${d.status}</td>
        <td class="px-4 py-2">${d.completionTime ?? "-"}</td>
      `;
      tableBody.appendChild(row);
    });

  const modal = document.getElementById("historyModal");
  modal.classList.remove("hidden");
  modal.classList.add("flex");
}

function closeHistoryModal() {
  const modal = document.getElementById("historyModal");
  modal.classList.add("hidden");
  modal.classList.remove("flex");
}



function openDeliveryModal(index) {
  editingDeliveryIndex = index;
  const d = deliveries[index];
  document.getElementById("modalTitle").textContent = d.rider ? "Update Delivery" : "Assign Delivery";
  document.getElementById("modalAddress").value = d.address;
  document.getElementById("modalRider").value = d.rider;
  document.getElementById("modalStatus").value = d.status;
  const modal = document.getElementById("deliveryModal");
  modal.classList.remove("hidden");
  modal.classList.add("flex");
}

async function saveToDatabase(delivery) {
  try {
    const response = await fetch('update_delivery.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: new URLSearchParams({
        deliveryId: delivery.deliveryId,
        deliveryStatus: delivery.status,
        assignedTo: delivery.rider
      })
    });

    const result = await response.json();

    if (!result.success) {
      console.error("Error saving:", result);
      alert("Error saving: " + (result.error || "Unknown error"));
    } else {
      console.log("Saved successfully:", result);
      await loadDeliveries();
    }
  } catch (err) {
    console.error("Fetch error:", err);
    alert("Failed to update delivery.");
  }
}

document.getElementById("deliveryForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const address = document.getElementById("modalAddress").value;
  const rider = document.getElementById("modalRider").value;
  const status = document.getElementById("modalStatus").value;

  if (editingDeliveryIndex === null) {
    const newDelivery = {
      orderId: `#${Math.floor(Math.random() * 9000 + 1000)}`,
      address,
      rider,
      status
    };
    deliveries.push(newDelivery);
    saveToDatabase(newDelivery);
  } else {
    deliveries[editingDeliveryIndex].address = address;
    deliveries[editingDeliveryIndex].rider = rider;
    deliveries[editingDeliveryIndex].status = status;

    saveToDatabase(deliveries[editingDeliveryIndex]);
  }

  renderDeliveries(searchInput.value, statusFilter.value);
  closeDeliveryModal();
});

searchInput.addEventListener("input", () => renderDeliveries(searchInput.value, statusFilter.value));
statusFilter.addEventListener("change", () => renderDeliveries(searchInput.value, statusFilter.value));

// Initial load
loadDeliveries();
