<?php
// Include database connection
include('../config/dbcon.php');

// Fetch orders, grouped by table number, with their items listed individually, excluding completed orders
$orderQuery = "
    SELECT orders.order_id, orders.table_no, 
           order_items.item_id, order_items.item_name, 
           order_items.quantity, order_items.order_status
    FROM orders
    LEFT JOIN order_items ON orders.order_id = order_items.order_id
    WHERE order_items.order_status IN ('Pending', 'Preparing', 'Ready To Serve', 'Done')
    AND orders.is_completed = 0
    ORDER BY orders.table_no DESC, orders.order_id ASC, order_items.item_name ASC
";

// Execute the query and handle errors
$orderResult = mysqli_query($conn, $orderQuery);

// Check if the query execution was successful
if (!$orderResult) {
    die("Error executing query: " . mysqli_error($conn));  // Displays the error message if the query fails
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen Dashboard - Urban Daybreak</title>
    <link rel="stylesheet" href="Kitchen.css">
    <style>
        /* CSS to add a gap between tables */
        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px; /* Add gap between tables */
        }

        /* Column widths */
        .order-table th, .order-table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #d8bc97;
        }

        /* Set the fixed width for each column */
        .order-table th:nth-child(1), .order-table td:nth-child(1) {
            width: 40%; /* Order Item column takes 40% */
        }

        .order-table th:nth-child(2), .order-table td:nth-child(2) {
            width: 30%; /* Order Status column takes 30% */
        }

        .order-table th:nth-child(3), .order-table td:nth-child(3) {
            width: 30%; /* Confirmation column takes 30% */
        }

        .order-table th {
            background-color: #d8bc97;
            color: #3a3a3a;
            border: 1px solid #3a3a3a;
        }

        /* Ensure the buttons fit inside the table and align well */
        .btn-forward, .btn-backward {
            width: 45%;
            margin: 0 2.5%;
        }
    </style>
</head>
<body>
    <div class="logo">
        <h2>Kitchen Dashboard</h2>
    </div>

    <div class="content">
        <table class="order-table">
            <tbody>
                <?php
                $current_order_id = "";  // Keep track of the current order ID to group items

                // Check if there are any orders
                if (mysqli_num_rows($orderResult) > 0) {
                    while ($order = mysqli_fetch_assoc($orderResult)) {
                        $order_id = $order['order_id'];
                        $table_no = $order['table_no'];
                        $item_id = $order['item_id'];  // Unique item ID
                        $item_name = $order['item_name'];
                        $order_status = $order['order_status'];
                        $quantity = $order['quantity'];

                        // Display table number once for each new order ID
                        if ($current_order_id != $order_id) {
                            if ($current_order_id != "") {
                                // Close the previous group
                                echo "</tbody></table>";
                            }
                            $current_order_id = $order_id;
                            echo "<table class='order-table'>
                                    <thead>
                                        <tr>
                                            <th colspan='4'><b>Table {$table_no}</b></th>
                                        </tr>
                                        <tr>
                                            <th class='order-item'><b>Order Item</b></th>
                                            <th><b>Order Status</b></th>
                                            <th><b>Confirmation</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>";
                        }

                        // Output the item row for each order
                        echo "<tr id='order-{$item_id}'>
                                <td>{$item_name} x {$quantity}</td>
                                <td><span class='status'>{$order_status}</span></td>
                                <td>
                                    <button class='btn-forward' onclick='updateStatus({$item_id}, \"next\", \"{$item_name}\")'>Proceed</button>
                                    <button class='btn-backward' onclick='updateStatus({$item_id}, \"previous\", \"{$item_name}\")'>Back</button>
                                </td>
                              </tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    echo "<tr><td colspan='4'>No orders found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Function to update order status
        function updateStatus(itemId, direction, itemName) {
            // Define the status sequence
            const statuses = ['Pending', 'Preparing', 'Ready To Serve', 'Done'];
            
            const statusElement = document.querySelector(`#order-${itemId} .status`);
            const currentStatus = statusElement.textContent;

            let currentIndex = statuses.indexOf(currentStatus);

            // Proceed or back based on direction
            if (direction === 'next') {
                if (currentIndex < statuses.length - 1) {
                    currentIndex++;
                }
            } else if (direction === 'previous') {
                if (currentIndex > 0) {
                    currentIndex--;
                }
            }

            const newStatus = statuses[currentIndex];
            statusElement.textContent = newStatus;

            // Send the updated status to the server
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "update-order-status.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    if (xhr.responseText === "success") {
                        // Check if the table is fully completed
                        checkTableCompletion(itemId);
                        // Reload the page after a successful update
                        location.reload();  // This reloads the page after the status update
                    } else {
                        alert("Error updating order status for item: " + itemName);
                    }
                }
            };
            xhr.send("item_id=" + itemId + "&status=" + newStatus + "&item_name=" + encodeURIComponent(itemName));
        }

        function checkTableCompletion(itemId) {
            // Check if all items for a table are done
            const tableNo = document.querySelector(`#order-${itemId}`).cells[0].textContent;

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "check-table-completion.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    if (xhr.responseText === "completed") {
                        // Table is complete, remove it from the list
                        removeTable(tableNo);
                    }
                }
            };
            xhr.send("table_no=" + tableNo);
        }

        function removeTable(tableNo) {
            // Hide or remove the table row
            const tableRows = document.querySelectorAll('tr');
            tableRows.forEach(function(row) {
                if (row.cells[0].textContent === tableNo) {
                    row.style.display = "none"; // Hide the table row
                }
            });
        }

        // Set an interval to check for updates and reload the page every 10 seconds (10000 milliseconds)
        setInterval(function() {
            location.reload();  // This will refresh the page every 10 seconds
        }, 3000);
    </script>
</body>
</html>
