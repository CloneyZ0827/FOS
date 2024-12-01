<?php
include('../config/dbcon.php');

// Check if item_id, status, and item_name are set
if (isset($_POST['item_id']) && isset($_POST['status']) && isset($_POST['item_name'])) {
    $item_id = $_POST['item_id'];  // Use item_id instead of order_item_id
    $status = $_POST['status'];
    $item_name = $_POST['item_name'];

    // Prepare the SQL statement to prevent SQL injection
    $query = "UPDATE order_items SET order_status = ? WHERE item_id = ? AND order_status != 'Done'";

    if ($stmt = mysqli_prepare($conn, $query)) {
        // Bind parameters
        mysqli_stmt_bind_param($stmt, "si", $status, $item_id); // "s" for string, "i" for integer

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            // If the status is "Done", update `is_completed` in the `orders` table
            if ($status === 'Done') {
                // Check if all items are done in the order
                $checkQuery = "SELECT COUNT(*) AS incomplete_items FROM order_items WHERE order_id = (SELECT order_id FROM order_items WHERE item_id = ?) AND order_status != 'Done'";
                if ($checkStmt = mysqli_prepare($conn, $checkQuery)) {
                    mysqli_stmt_bind_param($checkStmt, "i", $item_id);
                    mysqli_stmt_execute($checkStmt);
                    mysqli_stmt_bind_result($checkStmt, $incomplete_items);
                    mysqli_stmt_fetch($checkStmt);
                    mysqli_stmt_close($checkStmt);

                    // If there are no incomplete items, mark the order as completed
                    if ($incomplete_items == 0) {
                        $updateOrderQuery = "UPDATE orders SET is_completed = 1 WHERE order_id = (SELECT order_id FROM order_items WHERE item_id = ?)";
                        if ($updateStmt = mysqli_prepare($conn, $updateOrderQuery)) {
                            mysqli_stmt_bind_param($updateStmt, "i", $item_id);
                            mysqli_stmt_execute($updateStmt);
                            mysqli_stmt_close($updateStmt);
                        }
                    }
                }
            }

            echo "success";
        } else {
            echo "error";
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        echo "error";
    }
} else {
    echo "error";
}

mysqli_close($conn);
?>
