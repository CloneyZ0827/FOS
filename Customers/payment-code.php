<?php
session_start();
include('../config/dbcon.php');

// Ensure the user is logged in
if (!isset($_SESSION['customerUser'])) {
    echo "Error: User is not logged in.";
    exit();
}

// Ensure customer id exists in session
if (isset($_SESSION['customerUser']['id']) && !empty($_SESSION['customerUser']['id'])) {
    $customerId = $_SESSION['customerUser']['id'];  // Get the customer ID from session
} else {
    echo "Error: Customer ID is not set.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['makePayment'])) {
    $paymentMethod = $_POST['paymentMethod'];

    // Get the table number from the session
    if (isset($_SESSION['tableNumber']) && !empty($_SESSION['tableNumber'])) {
        $tableNumber = $_SESSION['tableNumber'];
    } else {
        echo "Error: Table number is not set or empty.";
        exit();
    }

    // Check if menu items are available in the session
    if (isset($_SESSION['menuItems']) && count($_SESSION['menuItems']) > 0) {
        $totalPrice = 0;
        foreach ($_SESSION['menuItems'] as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        // Escape table number and other input to avoid SQL injection
        $tableNumber = mysqli_real_escape_string($conn, $tableNumber);

        // Generate the invoice number
        date_default_timezone_set('Asia/Kuala_Lumpur');
        $currentDate = date('Ymd');
        $currentTime = date('His');

        // Get the current count of orders for today
        $orderCountQuery = "SELECT COUNT(*) AS order_count FROM orders WHERE DATE(order_date) = CURDATE()";
        $result = mysqli_query($conn, $orderCountQuery);
        $row = mysqli_fetch_assoc($result);
        $orderCount = $row['order_count'] + 1;

        $formattedOrderCount = str_pad($orderCount, 3, '0', STR_PAD_LEFT);

        // Generate the invoice number
        $invoice_no = $currentDate . $currentTime . $formattedOrderCount;

        // Insert into orders table, including the customer_id
        $orderQuery = "
            INSERT INTO orders (table_no, total_price, invoice_no, order_date, customer_id) 
            VALUES ('$tableNumber', '$totalPrice', '$invoice_no', NOW(), '$customerId')
        ";

        if (mysqli_query($conn, $orderQuery)) {
            $orderId = mysqli_insert_id($conn);

            // Insert order items into order_items table
            foreach ($_SESSION['menuItems'] as $itemId => $item) {
                $itemName = $item['name'];
                $quantity = $item['quantity'];
                $price = $item['price'];
                $orderStatus = 'Pending';

                $orderItemQuery = "INSERT INTO order_items (order_id, item_name, quantity, price, order_status) 
                                   VALUES ('$orderId', '$itemName', '$quantity', '$price', '$orderStatus')";
                mysqli_query($conn, $orderItemQuery);
            }

            // Insert payment details
            $paymentQuery = "INSERT INTO payments (order_id, payment_method) VALUES ('$orderId', '$paymentMethod')";
            mysqli_query($conn, $paymentQuery);

            // Clear the session
            unset($_SESSION['menuItems']);
            unset($_SESSION['tableNumber']);

            // Redirect to order summary page
            header("Location: order-summary.php?order_id=" . $orderId);
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Your cart is empty.";
    }
}
?>
