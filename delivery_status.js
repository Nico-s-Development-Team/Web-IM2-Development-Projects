document.addEventListener('DOMContentLoaded', () => {
  const statusElement = document.getElementById('delivery-status-indicator');
  const orderId = new URLSearchParams(window.location.search).get('order_id');

  function fetchDeliveryStatus() {
    fetch(`fetch_delivery_status.php?order_id=${orderId}`)
      .then(res => res.json())
      .then(data => {
        if (data.status) {
          statusElement.innerHTML = `Delivery Status: <b>${data.status}</b>`;
        } else {
          statusElement.innerHTML = `Delivery Status: <b>Preparing your Order...</b>`;
        }
      })
      .catch(err => {
        console.error('Error:', err);
        statusElement.innerHTML = `Delivery Status: <b>Unable to load status</b>`;
      });
  }

  fetchDeliveryStatus();
  setInterval(fetchDeliveryStatus, 10000); // every 10 seconds
});
