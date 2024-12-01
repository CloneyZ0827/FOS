<?php
include('includes/header.php');
include('../config/dbcon.php');

// Check if the user is logged in
if (!isset($_SESSION['customerUser'])) {
    header("Location: ../CLogin/login.php"); // Redirect to login if not logged in
    exit();
}

// Ensure order_id is provided in the GET request
if (!isset($_GET['order_id'])) {
    echo "Error: Order ID is missing.";
    exit();
}

$order_id = intval($_GET['order_id']); // Ensure order_id is an integer

// Retrieve Order Details including invoice_no
$orderQuery = "
    SELECT 
        orders.table_no, 
        orders.total_price, 
        orders.invoice_no, 
        payments.payment_method,
        customer.cName AS customer_name, 
        customer.phoneNo AS customer_phone, 
        customer.email AS customer_email
    FROM orders
    LEFT JOIN payments ON orders.order_id = payments.order_id
    LEFT JOIN customer ON orders.customer_id = customer.id
    WHERE orders.order_id = $order_id
";
$orderResult = mysqli_query($conn, $orderQuery);
$orderDetails = mysqli_fetch_assoc($orderResult);

if (!$orderDetails) {
    echo "Error: Order not found.";
    exit();
}

// Retrieve Ordered Items
$orderItemsQuery = "
    SELECT item_name, quantity, price 
    FROM order_items 
    WHERE order_id = $order_id
";
$orderItemsResult = mysqli_query($conn, $orderItemsQuery);

// Retrieve payment method directly from the database
$payment_method = $orderDetails['payment_method'];  // Get payment method from DB

// Process the send receipt form
if (isset($_POST['send_receipt'])) {
    $recipient_email = mysqli_real_escape_string($conn, $_POST['recipient_email']);
    $subject = "Order Receipt - Invoice #{$orderDetails['invoice_no']}";
    $body = "Dear {$orderDetails['customer_name']},\n\nThank you for your order at Urban Daybreak.\n\n" .
            "Here are your order details:\n\n" .
            "Invoice No: {$orderDetails['invoice_no']}\n" .
            "Payment Method: {$payment_method}\n" .
            "Table No: {$orderDetails['table_no']}\n" .
            "Total Price: RM " . number_format($orderDetails['total_price'], 2) . "\n\n" .
            "Ordered Items:\n";

    while ($item = mysqli_fetch_assoc($orderItemsResult)) {
        $body .= "{$item['item_name']} (x{$item['quantity']}) - RM " . number_format($item['price'] * $item['quantity'], 2) . "\n";
    }

    $body .= "\nThank you for choosing Urban Daybreak!";

    // Send the email
    if (mail($recipient_email, $subject, $body, "From: no-reply@urbandaybreak.com")) {
        echo "<script>alert('Receipt sent successfully!');</script>";
    } else {
        echo "<script>alert('Failed to send receipt. Please try again later.');</script>";
    }
}
?>

<div class="container-fluid px-4">
    <div class="row mt-4">
        <div class="col-lg-12 mb-2">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h2>Order Summary
                        <button class="btn btn-danger float-end" onclick="window.location.href='index.php?table_no=<?php echo $orderDetails['table_no']; ?>';">Back to Menu</button>
                    </h2>
                </div>

                <div class="card-body">
                    <div class="company-details text-center mb-1">
                        <p>
                          <strong>Urban Daybreak</strong>
                        <br>
                          34, Lebuh Bishop, George Town, 
                        <br>
                          10200 George Town, Pulau Pinang
                        </p>
                    </div>
                    <div class="row customer-invoice-section">
                        <!-- Customer Details -->
                        <div class="col-md-6">
                            <p><strong>Customer Details</strong>
                            <br>
                            Name: <?php echo $orderDetails['customer_name']; ?>
                            <br>
                            Phone: <?php echo $orderDetails['customer_phone']; ?>
                            <br>
                            Email: <?php echo $orderDetails['customer_email']; ?>
                            <br><br>
                            <strong>Table No:</strong> <?php echo $orderDetails['table_no']; ?>
                            </p>
                        </div>
                        <!-- Invoice Details -->
                        <div class="col-md-6 text-end">
                            <p><strong>Invoice Details</strong>
                            <br>
                            Invoice No: <?php echo $orderDetails['invoice_no']; ?>  <!-- Display invoice_no from DB -->
                            <br>
                            Invoice Date: <?php echo date("d M Y"); ?>
                            <br>
                            Invoice Time: <?php echo date("H:i:s"); ?>
                            <br>
                            Address: 34, Lebuh Bishop, George Town, Pulau Pinang
                            </p>
                        </div>
                    </div>

                    <!-- Cart Items Table -->
                    <table class="table table-bordered order-table">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Price (RM)</th>
                                <th>Quantity</th>
                                <th>Total Price (RM)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $grandTotal = 0;
                            while ($item = mysqli_fetch_assoc($orderItemsResult)) {
                                $itemTotal = $item['price'] * $item['quantity'];
                                $grandTotal += $itemTotal;

                                echo "<tr>
                                        <td>{$item['item_name']}</td>
                                        <td>" . number_format($item['price'], 2) . "</td>
                                        <td>{$item['quantity']}</td>
                                        <td>" . number_format($itemTotal, 2) . "</td>
                                      </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Grand Total and Payment Method -->
                <div class="card-body d-flex justify-content-between">
                    <p><strong>Payment Mode: <?php echo $payment_method; ?></strong></p>  <!-- Payment method from DB -->
                    <p class="fw-bold"><strong>Grand Total: RM <?php echo number_format($grandTotal, 2); ?></strong></p>
                </div>

                <!-- Save as PDF and Send Receipt below each other -->
                <div class="card-footer text-start">
                    <!-- Save as PDF Button -->
                    <button class="btn btn-success" id="saveAsPdfBtn">Save As PDF</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<script>
document.getElementById("saveAsPdfBtn").addEventListener("click", function() {
    // Trigger a request to the server to save the page as PDF
    window.location.href = 'save-pdf.php?order_id=<?php echo $order_id; ?>'; // Redirect to the save-pdf.php script
});
</script>
