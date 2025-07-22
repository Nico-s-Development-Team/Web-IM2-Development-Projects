// delivery_history.js

// Open the modal and fetch history
document.getElementById('openHistoryBtn').addEventListener('click', () => {
    document.getElementById('deliveryHistoryModal').classList.remove('hidden');
    loadDeliveryHistory();
});

// Close the modal
document.getElementById('closeModalBtn').addEventListener('click', () => {
    document.getElementById('deliveryHistoryModal').classList.add('hidden');
});

// Fetch and display delivery history
async function loadDeliveryHistory() {
    const historyContainer = document.getElementById('historyContainer');
    historyContainer.innerHTML = '<p>Loading...</p>';

    try {
        const response = await fetch('fetch_delivery_history.php');
        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
        
        const data = await response.json();
        historyContainer.innerHTML = ''; // Clear previous content

        if (Array.isArray(data) && data.length > 0) {
            data.forEach(item => {
                const entry = document.createElement('div');
                entry.className = 'bg-white border rounded-lg shadow p-4';

                entry.innerHTML = `
                    <p><strong>Order ID:</strong> ${item.Order_ID}</p>
                    <p><strong>Assigned To:</strong> ${item.Assigned_To}</p>
                    <p><strong>Status:</strong> ${item.Delivery_Status}</p>
                    <p><strong>Completed At:</strong> ${item.Completion_Time}</p>
                    <p><strong>Address:</strong> ${item.Customer_Address}</p>
                `;

                historyContainer.appendChild(entry);
            });
        } else {
            historyContainer.innerHTML = '<p>No delivery history available.</p>';
        }
    } catch (error) {
        console.error('Error fetching delivery history:', error);
        historyContainer.innerHTML = '<p class="text-red-600">Error loading delivery history.</p>';
    }
}
