<?php
session_start();
require_once 'db_conn.php';

if (!isset($_SESSION['customer_id'])) {
    echo "<p>Please log in to view your order history.</p>";
    exit;
}

$customer_id = $_SESSION['customer_id'];

$sql = "
    SELECT 
        o.Order_ID, 
        o.Order_Date, 
        o.Order_Status,
        oi.Quantity, 
        oi.Subtotal, 
        m.Item_Name 
    FROM Order_T o
    JOIN OrderItem_T oi ON o.Order_ID = oi.Order_ID
    JOIN MenuItem_T m ON oi.MenuItem_ID = m.MenuItem_ID
    WHERE o.Customer_ID = ?
    ORDER BY o.Order_Date DESC, o.Order_ID
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

$orderHistory = [];

while ($row = $result->fetch_assoc()) {
    $orderId = $row['Order_ID'];
    $orderDate = $row['Order_Date'];
    $orderStatus = $row['Order_Status'];

    if (!isset($orderHistory[$orderId])) {
        $orderHistory[$orderId] = [
            'date' => $orderDate,
            'status' => $orderStatus,
            'items' => [],
            'total' => 0,
        ];
    }

    $orderHistory[$orderId]['items'][] = [
        'name' => $row['Item_Name'],
        'quantity' => $row['Quantity'],
        'subtotal' => $row['Subtotal'],
    ];

    $orderHistory[$orderId]['total'] += $row['Subtotal'];
}

// Render HTML
if (empty($orderHistory)) {
    echo "<div class='text-center text-gray-400 py-20'>
            <p class='text-xl font-semibold'>You haven’t ordered anything yet.</p>
            <p class='text-sm mt-2'>Hungry? Let’s fix that 🍔🍟</p>
          </div>";
} else {
    echo "<div class='grid gap-8'>";

    foreach ($orderHistory as $orderId => $order) {
        $status = $order['status'];
        $statusColors = [
            'Pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'],
            'Preparing' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700'],
            'Ready' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-700'],
            'Delivering' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
            'Completed' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700'],
            'Cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-700'],
        ];
        $bgClass = $statusColors[$status]['bg'] ?? 'bg-gray-100';
        $textClass = $statusColors[$status]['text'] ?? 'text-gray-700';

        echo "<div class='relative bg-gradient-to-br from-white to-gray-50 border border-gray-200 shadow-xl rounded-3xl p-6 overflow-hidden'>";

        // Background watermark order number
        echo "<div class='absolute top-3 right-5 text-gray-100 text-6xl font-extrabold opacity-10 select-none'>#$orderId</div>";

        // Header
        echo "<div class='flex flex-col md:flex-row md:justify-between md:items-center mb-6'>";
        echo "<div>";
        echo "<h2 class='text-2xl font-bold text-gray-800'>Order #$orderId</h2>";
        echo "<p class='text-sm text-gray-500 mt-1'>Placed on " . date("F j, Y, g:i A", strtotime($order['date'])) . "</p>";
        echo "</div>";
        echo "<div class='mt-4 md:mt-0'>";
        echo "<span class='inline-flex items-center px-4 py-1.5 $bgClass $textClass text-sm font-medium rounded-full shadow-sm'>
                <svg class='w-4 h-4 mr-1' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'>
                  <path stroke-linecap='round' stroke-linejoin='round' d='M5 13l4 4L19 7'/>
                </svg>
                $status
              </span>";
        echo "</div>";
        echo "</div>";

        // Item List
        echo "<div class='bg-white rounded-xl border border-gray-100 p-4 divide-y divide-gray-100 shadow-sm'>";
        foreach ($order['items'] as $item) {
            echo "<div class='flex justify-between items-center py-3'>";
            echo "<div class='text-gray-700 text-sm'>
                    <span class='font-medium'>{$item['name']}</span> 
                    <span class='text-xs text-gray-400 ml-2'>× {$item['quantity']}</span>
                  </div>";
            echo "<div class='text-right text-sm font-semibold text-gray-800'>₱" . number_format($item['subtotal'], 2) . "</div>";
            echo "</div>";
        }
        echo "</div>";

        // Order Summary
        echo "<div class='flex justify-end mt-6'>";
        echo "<div class='text-right'>";
        echo "<p class='text-sm text-gray-500'>Total Amount</p>";
        echo "<p class='text-2xl font-bold text-gray-900'>₱" . number_format($order['total'], 2) . "</p>";
        echo "</div>";
        echo "</div>";

        echo "</div>";
    }

    echo "</div>"; // End of grid container
}
?>
