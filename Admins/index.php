<?php 
include('includes/header.php');
include('../config/dbcon.php');

// Get the current date
$currentDate = date('Y-m-d');

// Handle search filter
$searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Construct the WHERE clause for the filter
$filterQuery = "WHERE DATE(o.order_date) = '$currentDate'";

if ($searchTerm) {
    $filterQuery .= " AND (o.invoice_no LIKE '%$searchTerm%' OR o.table_no LIKE '%$searchTerm%')";
}

// Fetch current day's orders (incomplete), grouping by order_id and separating item names with <br>, including quantity
$queryCurrentOrders = "
    SELECT o.order_id, o.table_no, o.is_completed, 
           GROUP_CONCAT(CONCAT(oi.item_name, ' x ', oi.quantity) ORDER BY oi.item_name ASC SEPARATOR '<br>') AS item_details, 
           oi.order_status, RIGHT(o.invoice_no, 3) AS order_no
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    $filterQuery AND o.is_completed = 0
    GROUP BY o.order_id
    ORDER BY o.order_date ASC";
$resultCurrentOrders = mysqli_query($conn, $queryCurrentOrders);

// Fetch completed orders for the current day, grouping by order_id and separating item names with <br>, including quantity
$queryCompletedOrders = "
    SELECT o.order_id, o.table_no, o.is_completed, 
           GROUP_CONCAT(CONCAT(oi.item_name, ' x ', oi.quantity) ORDER BY oi.item_name ASC SEPARATOR '<br>') AS item_details, 
           oi.order_status, RIGHT(o.invoice_no, 3) AS order_no
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    $filterQuery AND o.is_completed = 1
    GROUP BY o.order_id
    ORDER BY o.order_date DESC";
$resultCompletedOrders = mysqli_query($conn, $queryCompletedOrders);
?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: rgba(58, 58, 58); color: #d8bc97;">
            <h4 class="mb-0">Order Status</h4>
                <form method="GET" action="" class="d-inline-block">
                    <input type="text" name="search" value="<?= htmlspecialchars($searchTerm); ?>" placeholder="Search by Order No or Table No" class="form-control" style="max-width: 500px;">
                </form>
            
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>
            
            <!-- Main Card Wrapper -->
            <div class="row mt-4">
                <!-- Current Orders Section -->
                <div class="col-xl-6 col-md-12">
                    <div class="mb-4" style="border-radius: 15px;">
                        <div class="card-header" style="background-color: rgba(58, 58, 58); color: #d8bc97;">
                            <h5 style="font-size: 30px;">Current Orders</h5>
                        </div>
                        <div class="card-body" style="background-color: rgba(58, 58, 58, 0.9); color: #d8bc97;">
                            <!-- Nested Card for Current Orders Table -->
                            <div class="card mb-3">
                                <div class="card-body">
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

                <!-- Completed Orders Section -->
                <div class="col-xl-6 col-md-12 " >
                    <div class=" mb-4 " style="border-radius: 15px;">
                        <div class="card-header" style="background-color: rgba(58, 58, 58); color: #d8bc97;">
                            <h5 style="font-size: 30px;">Completed Orders</h5>
                        </div>
                        <div class="card-body" style="background-color: rgba(58, 58, 58, 0.9); color: #d8bc97;">
                            <!-- Nested Card for Completed Orders Table -->
                            <div class="card mb-3">
                                <div class="card-body">
                                    <?php if (mysqli_num_rows($resultCompletedOrders) > 0): ?>
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
                                                <?php while ($order = mysqli_fetch_assoc($resultCompletedOrders)): ?>
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
                                        <p>No completed orders found.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
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
        }, 3000);
</script>

<?php include('includes/footer.php'); ?>
