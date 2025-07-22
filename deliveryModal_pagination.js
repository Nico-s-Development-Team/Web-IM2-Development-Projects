
let currentPage = 1;
const perPage = 5;

function fetchDeliveryHistory(page = 1) {
    fetch(`fetch_delivery_history.php?page=${page}`)
        .then(res => res.json())
        .then(data => {
            renderHistory(data);
            renderPaginationControls();
        });
}

function renderHistory(deliveries) {
    const container = document.getElementById('historyContainer');
    container.innerHTML = '';

    if (deliveries.length === 0) {
        container.innerHTML = '<p class="text-gray-500">No delivery history found.</p>';
        return;
    }

    deliveries.forEach(delivery => {
        const div = document.createElement('div');
        div.className = 'p-4 border rounded bg-gray-100';
        div.innerHTML = `
            <p><strong>Order ID:</strong> ${delivery.Order_ID}</p>
            <p><strong>Status:</strong> ${delivery.Delivery_Status}</p>
            <p><strong>Delivered by:</strong> ${delivery.Assigned_To}</p>
            <p><strong>Completion Time:</strong> ${delivery.Completion_Time}</p>
            <p><strong>Address:</strong> ${delivery.Customer_Address}</p>
        `;
        container.appendChild(div);
    });
}

function renderPaginationControls() {
    const pagination = document.getElementById('paginationControls') || document.createElement('div');
    pagination.id = 'paginationControls';
    pagination.className = 'flex justify-between items-center mt-4';

    pagination.innerHTML = `
        <button class="px-4 py-2 bg-gray-300 rounded" onclick="changePage(-1)">Previous</button>
        <span class="font-semibold">Page ${currentPage}</span>
        <button class="px-4 py-2 bg-gray-300 rounded" onclick="changePage(1)">Next</button>
    `;

    document.getElementById('historyContainer').appendChild(pagination);
}

function changePage(delta) {
    if (currentPage + delta < 1) return;
    currentPage += delta;
    fetchDeliveryHistory(currentPage);
}

// Call this when opening the modal
function openDeliveryHistoryModal() {
    document.getElementById('deliveryHistoryModal').classList.remove('hidden');
    currentPage = 1;
    fetchDeliveryHistory(currentPage);
}

document.getElementById('closeModalBtn').addEventListener('click', () => {
    document.getElementById('deliveryHistoryModal').classList.add('hidden');
});

