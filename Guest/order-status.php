<?php 
session_start();

include('includes/header.php');
include('../config/dbcon.php');

// Get the current date
$currentDate = date('Y-m-d');

// Get the table number from session (or some other identifier like session_id)
$table_no = $_SESSION['table_no'];  // Ensure this session variable is set when the guest starts an order

// Fetch current day's orders (incomplete) for the guest, grouping by order_id and separating item names with <br>
$queryCurrentOrders = "
    SELECT o.order_id, o.table_no, o.is_completed, 
           GROUP_CONCAT(CONCAT(oi.item_name, ' x ', oi.quantity) ORDER BY oi.item_name ASC SEPARATOR '<br>') AS item_details, 
           oi.order_status, RIGHT(o.invoice_no, 3) AS order_no
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    WHERE o.is_completed = 0  -- Only show incomplete orders
    AND o.table_no = '$table_no'  -- Filter by the guest's table_no
    AND DATE(o.order_date) = '$currentDate'  -- Filter by current day's orders
    GROUP BY o.order_id
    ORDER BY o.order_date ASC";
$resultCurrentOrders = mysqli_query($conn, $queryCurrentOrders);
?>

<div class="container-fluid px-2">
    <div class="card mt-4 shadow-sm">
        <!-- Current Orders Section -->
        <div style="border-radius: 15px;">
            <div class="card-header" style="background-color: rgba(58, 58, 58); color: #d8bc97;">
                <h5 style="font-size: 30px;">Order Status</h5>
            </div>
            <div class="card-body" style="background-color: rgba(58, 58, 58, 0.9); color: #d8bc97;">
                <div class="card mb-3">
                    <?php if (mysqli_num_rows($resultCurrentOrders) > 0): ?>
                        <table class="order-table">
                            <thead>
                                <tr>
                                    <th>Order No</th>
                                    <th>Table No</th>
                                    <th>Item Details</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($order = mysqli_fetch_assoc($resultCurrentOrders)): ?>
                                    <tr>
                                        <td><?= $order['order_no']; ?></td> <!-- Last 3 digits of invoice_no -->
                                        <td><?= $order['table_no']; ?></td>
                                        <td><?= $order['item_details']; ?></td>
                                        <td><?= $order['order_status']; ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No current orders found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Styles Block -->
<style>
    .order-table {
        width: 100%;
        border-collapse: collapse;
        background-color: #f9f9f9;
        color: #333;
        border-radius: 8px;
    }

    .order-table th, .order-table td {
        padding: 12px;
        text-align: center;
        border: 1px solid ;
    }

    .order-table th {
        background-color: #3a3a3a;
        color: #d8bc97;
    }

    .order-table tbody tr {
        background-color: #d8bc97;
        color: #3a3a3a;
    }

    .order-table tbody td {
        background-color: #d8bc97;
    }
</style>

<script>
    setInterval(function() {
            location.reload();  // This will refresh the page every 10 seconds
        }, 5000);
</script>

<?php include('includes/footer.php'); ?>
