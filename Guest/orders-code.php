<?php
include('../config/function.php');

if (!isset($_SESSION['menuItems'])) {
    $_SESSION['menuItems'] = [];
}

if (isset($_POST['addToCart'])) {
    $menuId = validate($_POST['menu_id']);
    $quantity = 1;

    // Query to retrieve the menu item from the database
    $checkMenu = mysqli_query($conn, "SELECT * FROM menu WHERE id='$menuId' LIMIT 1");

    if ($checkMenu && mysqli_num_rows($checkMenu) > 0) {
        $row = mysqli_fetch_assoc($checkMenu);

        $menuData = [
            'menu_id' => $row['id'],
            'name' => $row['name'],
            'price' => $row['price'],
            'quantity' => $quantity,
        ];

        $exists = false;

        foreach ($_SESSION['menuItems'] as $key => $menuItem) {
            if ($menuItem['menu_id'] == $menuId) {
                $_SESSION['menuItems'][$key]['quantity'] += $quantity;
                $exists = true;
            }
        }

        if (!$exists) {
            $_SESSION['menuItems'][] = $menuData;
        }
    }
    displayCart();
}

// Adjust quantity of items in the cart or remove if quantity reaches zero
if (isset($_POST['updateQuantity'])) {
    $menuId = validate($_POST['menu_id']);
    $action = $_POST['action'];

    foreach ($_SESSION['menuItems'] as $key => $menuItem) {
        if ($menuItem['menu_id'] == $menuId) {
            if ($action == "increment") {
                $_SESSION['menuItems'][$key]['quantity'] += 1;
            } elseif ($action == "decrement") {
                if ($_SESSION['menuItems'][$key]['quantity'] == 1) {
                    unset($_SESSION['menuItems'][$key]); // Remove item if quantity reaches 1 and user presses decrement
                } else {
                    $_SESSION['menuItems'][$key]['quantity'] -= 1;
                }
            }
            break; // Exit the loop once the item is found and updated
        }
    }
    
    // Re-index array after removal to prevent gaps in session array keys
    $_SESSION['menuItems'] = array_values($_SESSION['menuItems']);
    displayCart();
}

if (isset($_POST['checkoutOrder'])) {
    $tableNo = $_SESSION['tableNumber']; // Get the selected table
    foreach ($_SESSION['menuItems'] as $item) {
        $menuId = $item['menu_id'];
        $quantity = $item['quantity'];
        $totalPrice = $item['price'] * $quantity;

        // Insert each order item into the 'orders' table
        $query = "INSERT INTO orders (table_no, menu_id, quantity, total_price) 
                  VALUES ('$tableNo', '$menuId', '$quantity', '$totalPrice')";
        mysqli_query($conn, $query);
    }

    // Clear the cart after the order is placed
    unset($_SESSION['menuItems']);
    
    // Redirect to a success or confirmation page
    header("Location: payment.php");
    exit();
}

function displayCart() {
    $output = "<div class='table-wrapper'><table class='table'>
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>";

    $grandTotal = 0;

    foreach ($_SESSION['menuItems'] as $item) {
        $itemTotal = $item['price'] * $item['quantity'];
        $output .= "<tr>
                        <td>{$item['name']}</td>
                        <td>
                            <button class='quantity-btn' onclick=\"updateQuantity({$item['menu_id']}, 'decrement')\">-</button>
                            {$item['quantity']}
                            <button class='quantity-btn' onclick=\"updateQuantity({$item['menu_id']}, 'increment')\">+</button>
                        </td>
                        <td>RM " . number_format($itemTotal, 2) . "</td>
                    </tr>";
        $grandTotal += $itemTotal;
    }

    // Adding a row for the Grand Total aligned under Item Name
    $output .= "<tr>
                    <td><strong>Grand Total</strong></td>
                    <td></td>
                    <td><strong>RM " . number_format($grandTotal, 2) . "</strong></td>
                </tr>";

    $output .= "</tbody></table></div>";

    echo $output;
}

// Check if the cart is empty
if (isset($_POST['checkEmptyCart'])) {
    if (empty($_SESSION['menuItems'])) {
        echo '';  // Return an empty response if the cart is empty
    } else {
        echo 'not_empty';  // Return a non-empty response if there are items in the cart
    }
    exit();
}

?>
