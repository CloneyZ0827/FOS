<?php include('includes/header.php'); ?>
<?php include('../config/dbcon.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Orders
            </h4>
        </div>
        <div class="card-body">
            <!-- Search Form -->
            <form method="GET" action="">
                <div class="mb-3">
                    <label for="search_date" class="form-label">Search by Date</label>
                    <input type="date" name="search_date" id="search_date" class="form-control" 
                           value="<?php echo isset($_GET['search_date']) ? $_GET['search_date'] : ''; ?>" 
                           max="<?php echo date('Y-m-d'); ?>"> <!-- Limit to today or past dates -->
                </div>
                
                <!-- Month Selector -->
                <div class="mb-3">
                    <label for="search_month" class="form-label">Select Month</label>
                    <select name="search_month" id="search_month" class="form-control">
                        <?php
                            // Get the current month and year
                            $currentMonth = date('m');
                            $currentYear = date('Y');

                            // Loop through the previous 12 months
                            for ($i = 0; $i < 12; $i++) {
                                $monthValue = date('m', strtotime("-$i month"));
                                $monthLabel = date('F Y', strtotime("-$i month"));
                                echo "<option value='$monthValue-$currentYear'" . ($monthValue == $currentMonth ? ' selected' : '') . ">$monthLabel</option>";
                            }
                        ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Search</button>
            </form>
            <br>
            
            <!-- Display Orders by Date -->
            <?php
            // Get the selected month and year
            $selectedMonth = isset($_GET['search_month']) ? $_GET['search_month'] : date('m-Y');
            list($month, $year) = explode('-', $selectedMonth);

            // Get the search date if provided
            $search_date = isset($_GET['search_date']) ? $_GET['search_date'] : null;

            // Query to get orders grouped by date for the selected month and year
            $orderQuery = "
                SELECT orders.order_id, orders.table_no, orders.order_date, payments.payment_method, orders.invoice_no,
                       GROUP_CONCAT(DISTINCT order_items.order_status ORDER BY order_items.order_status) AS item_statuses
                FROM orders
                JOIN payments ON orders.order_id = payments.order_id
                LEFT JOIN order_items ON orders.order_id = order_items.order_id
                WHERE MONTH(orders.order_date) = '$month' 
                  AND YEAR(orders.order_date) = '$year'
                  " . ($search_date ? " AND DATE(orders.order_date) = '$search_date'" : "") . "
                GROUP BY orders.order_id
                ORDER BY orders.order_date DESC
            ";

            $orderResult = mysqli_query($conn, $orderQuery);

            if (mysqli_num_rows($orderResult) > 0) {
                $ordersByDate = [];

                // Group orders by date
                while ($order = mysqli_fetch_assoc($orderResult)) {
                    $order_date = date('d M Y', strtotime($order['order_date']));
                    $ordersByDate[$order_date][] = $order;
                }

                // Display each date's orders inside its own card
                foreach ($ordersByDate as $order_date => $orders) {
                    echo "<div class='card mb-4'>
                            <div class='card-header d-flex justify-content-between align-items-center'>
                                <h5 class='mb-0'>Orders for {$order_date}</h5>
                                <!-- Filter for Table No. or Order No. -->
                                <input type='text' class='form-control w-25' placeholder='Search Table No. or Order No.' 
                                       onkeyup='filterOrders(this, \"table-{$order_date}\")'>
                            </div>
                            <div class='card-body'>
                                <table class='table table-bordered' id='table-{$order_date}'>
                                    <thead>
                                        <tr>
                                            <th>Order No.</th>
                                            <th>Table No.</th>
                                            <th>Order Status</th>
                                            <th>Payment Method</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>";

                    // Display orders for this date
                    foreach ($orders as $order) {
                        // Determine order status
                        $order_status = 'Completed'; // Default
                        if (strpos($order['item_statuses'], 'Pending') !== false) {
                            $order_status = "Pending";
                        } elseif (strpos($order['item_statuses'], 'Preparing') !== false) {
                            $order_status = "Preparing";
                        } elseif (strpos($order['item_statuses'], 'Ready To Serve') !== false) {
                            $order_status = "Ready To Serve";
                        }

                        // Format Order No.
                        $orderNo = substr($order['invoice_no'], -3);
                        $formattedOrderNo = str_pad($orderNo, 3, '0', STR_PAD_LEFT);

                        echo "<tr>
                                <td>{$formattedOrderNo}</td>
                                <td>{$order['table_no']}</td>
                                <td>{$order_status}</td>
                                <td>{$order['payment_method']}</td>
                                <td>
                                    <a href='view-order.php?order_id={$order['order_id']}' class='btn btn-info btn-sm'>View</a>
                                </td>
                              </tr>";
                    }

                    echo "    </tbody>
                                </table>
                            </div>
                          </div>";
                }
            } else {
                echo "<div class='alert alert-warning'>No orders found</div>";
            }
            ?>
        </div>
    </div>
</div>

<!-- JavaScript for Filtering -->
<script>
    function filterOrders(input, tableId) {
        const filter = input.value.toUpperCase();
        const table = document.getElementById(tableId);
        const rows = table.getElementsByTagName("tr");

        for (let i = 1; i < rows.length; i++) { // Start at 1 to skip table header
            const orderNo = rows[i].getElementsByTagName("td")[0]?.textContent.toUpperCase();
            const tableNo = rows[i].getElementsByTagName("td")[1]?.textContent.toUpperCase();

            if (orderNo?.includes(filter) || tableNo?.includes(filter)) {
                rows[i].style.display = ""; // Show row
            } else {
                rows[i].style.display = "none"; // Hide row
            }
        }
    }
</script>

<?php include('includes/footer.php'); ?>
