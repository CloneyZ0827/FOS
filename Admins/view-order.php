<?php 
include('includes/header.php');
include('../config/dbcon.php');

// Check if the order_id is set
if (!isset($_GET['order_id'])) {
    echo "Error: Order ID is missing.";
    exit();
}

$order_id = intval($_GET['order_id']); // Ensure the order_id is an integer

// Retrieve Order Details including invoice_no
$orderQuery = "
    SELECT 
        orders.order_id,
        orders.table_no, 
        orders.total_price, 
        orders.invoice_no, 
        orders.order_date, 
        payments.payment_method
    FROM orders
    JOIN payments ON orders.order_id = payments.order_id
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
    SELECT item_name, quantity, price, order_status 
    FROM order_items 
    WHERE order_id = $order_id
";
$orderItemsResult = mysqli_query($conn, $orderItemsQuery);
?>

<div class="container-fluid px-4">
    <div class="row mt-4">
        <div class="col-lg-12 mb-2">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h2>Order Details
                        <a href="orders.php" class="btn btn-danger float-end">Back to Orders</a>
                    </h2>
                </div>

                <div class="card-body">
                    <div class="company-details text-center mb-1">
                        <p>
                            <strong>Urban Daybreak</strong><br>
                            34, Lebuh Bishop, George Town,<br>
                            10200 George Town, Pulau Pinang
                        </p>
                    </div>
                    <div class="row staff-invoice-section">
                        <!-- Staff Details (if needed) -->
                        <div class="col-md-6">
                            <p><strong>Table No:</strong> <?php echo $orderDetails['table_no']; ?></p>
                            <p><strong>Invoice No:</strong> <?php echo $orderDetails['invoice_no']; ?></p>
                            <p><strong>Order Date:</strong> <?php echo date('d M Y', strtotime($orderDetails['order_date'])); ?></p>
                        </div>
                        <!-- Invoice Details -->
                        <div class="col-md-6 text-end">
                            <p><strong>Invoice Details</strong><br>
                               Invoice Date: <?php echo date("d M Y", strtotime($orderDetails['order_date'])); ?><br>
                               Invoice Time: <?php echo date("H:i:s", strtotime($orderDetails['order_date'])); ?><br>
                               Payment Method: <?php echo $orderDetails['payment_method']; ?>
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
                                <th>Status</th>
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
                                        <td>{$item['order_status']}</td>
                                        <td>" . number_format($itemTotal, 2) . "</td>
                                      </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Grand Total -->
                <div class="card-body d-flex justify-content-between">
                    <p><strong>Grand Total: RM <?php echo number_format($grandTotal, 2); ?></strong></p>
                </div>

                <!-- Save as PDF and Send Receipt -->
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
