<?php
// Include database connection
include('../config/dbcon.php');

// Get the table number
$table_no = $_POST['table_no'];

// Check if all items for the table are marked as "Done"
$query = "
    SELECT COUNT(*) as total_items, 
           SUM(CASE WHEN order_items.order_status = 'Done' THEN 1 ELSE 0 END) as done_items
    FROM order_items
    LEFT JOIN orders ON order_items.order_id = orders.order_id
    WHERE orders.table_no = '$table_no' AND orders.is_completed = 0
";

$result = mysqli_query($conn, $query);
if ($result) {
    $data = mysqli_fetch_assoc($result);
    if ($data['total_items'] == $data['done_items']) {
        // All items are done, mark the table as completed
        $updateQuery = "
            UPDATE orders
            SET is_completed = 1
            WHERE table_no = '$table_no' AND is_completed = 0
        ";
        mysqli_query($conn, $updateQuery);

        echo "completed";
    } else {
        echo "incomplete";
    }
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
