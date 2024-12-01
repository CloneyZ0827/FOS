<?php
session_start();
include('includes/header.php');
include('orders-code.php');  // Include orders-related functions

// Check if the request is POST and table number is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tableNo'])) {
    $_SESSION['tableNumber'] = $_POST['tableNo']; 
} else {
    // Reset session tableNumber if no selection is made
    unset($_SESSION['tableNumber']);
}
?>


<div class="container-fluid px-4">
    <div class="row mt-4">
        <!-- Left Card Container (70% width) -->
        <div class="col-lg-8 mb-4" style="color: #d8bc97;">
            <div class="card shadow-sm">
                <div class="card-header" style="background-color: rgba(58, 58, 58);">
                    <h4 class="mb-0">Create Order</h4>
                </div>

                <!-- Form for Order Code Processing -->
                <div class="card-body">
                    <?php alertMessage(); ?>
                    <div class="container-fluid px-4">
                        <div class="row mt-4">
                        <?php
                        // Query to retrieve active menu items from active categories
                        $query = "
                            SELECT categories.name AS category_name, menu.id, menu.name, menu.image, menu.price 
                            FROM menu 
                            INNER JOIN categories ON menu.category_id = categories.id 
                            WHERE menu.status = 0 AND categories.status = 0
                            ORDER BY categories.name ASC
                        ";
                        $result = mysqli_query($conn, $query);

                        // Group menu items by category
                        $menuByCategory = [];
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($menuItem = mysqli_fetch_assoc($result)) {
                                $menuByCategory[$menuItem['category_name']][] = $menuItem;
                            }
                        }

                        // Display grouped menu items
                        if (!empty($menuByCategory)) {
                            foreach ($menuByCategory as $categoryName => $menuItems) {
                                ?>
                                <div class="category-section mb-4">
                                    <h5 class="mb-3" style="color: #3a3a3a;"><?= htmlspecialchars($categoryName); ?></h5>
                                    <div class="row">
                                        <?php foreach ($menuItems as $menuItem) { ?>
                                            <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                                                <div class="card shadow-sm h-100 d-flex flex-column" style="background-color: rgba(58, 58, 58, 0.9); font-size: 0.9rem; padding: 5px;">
                                                    <div class="card-header" style="background-color: rgba(58, 58, 58); padding: 5px;">
                                                        <h6 class="mb-0 text-truncate"><?= htmlspecialchars($menuItem['name']); ?></h6>
                                                    </div>
                                                    <div class="card-body flex-grow-1 p-1">
                                                        <img src="../<?= $menuItem['image'] ?>" class="mb-1" alt="Img" style="width: 100%; height: 120px; object-fit: cover; object-position: center; border-radius: 4px;">
                                                        <p class="mb-0 text-center">RM <?= number_format($menuItem['price'], 2); ?></p>
                                                    </div>
                                                    <div class="card-footer text-center p-1" style="background-color: rgba(58, 58, 58);">
                                                        <button onclick="addToCart(<?= $menuItem['id']; ?>)" class="btn btn-success btn-sm" 
                                                                style="background-color: #d8bc97; color: #3a3a3a; border: none;">
                                                            Add to Cart
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            echo "<p>No menu items found.</p>";
                        }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Card Container (30% width) -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header" style="background-color: #d8bc97;color: #3a3a3a">
                    <h4 class="mb-0">Summary (<span id="summaryTableNo">Select A Table</span>)</h4>
                    <form action="order-create.php" method="POST" id="orderForm">
                        <label for="">Table No</label>
                        <br>
                        <select name="tableNo" class="form-select mySelect2 mb-1" id="tableNoSelect" onchange="this.form.submit()">
                            <option value="" <?php echo empty($_SESSION['tableNumber']) ? 'selected' : ''; ?>>-- Select Table --</option>
                            <?php
                            $table = getAll('tableNo');
                            if ($table) {
                                foreach ($table as $tableNo) {
                                    echo "<option value=\"{$tableNo['table_no']}\" " . 
                                        (!empty($_SESSION['tableNumber']) && $_SESSION['tableNumber'] == $tableNo['table_no'] ? "selected" : "") . 
                                        ">{$tableNo['table_no']}</option>";
                                }
                            }
                            ?>
                        </select>
                    </form>
                </div>
                <div class="card-body" style="background-color: rgba(216, 188, 151,0.8);">
                    <!-- Cart Summary Table -->
                    <div id="cartSummary">
                        <?php displayCart(); // Display the cart content from the session ?>
                    </div>
                </div>
                <div class="card-footer text-center" style="background-color: rgba(216, 188, 151);">
                    <a class="btn btn-success disabled" style="background-color: #3a3a3a; color: #d8bc97; border: none;" id="checkoutButton" href="payment.php">
                        CHECKOUT
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateSummaryLabel() {
    var tableNo = document.getElementById('tableNoSelect').value;
    var checkoutLink = document.getElementById('checkoutButton');

    document.getElementById('summaryTableNo').innerText = tableNo ? tableNo : "Select a table";

    // Check if tableNo is selected and cart is not empty
    checkCartStatus(tableNo, function(cartNotEmpty) {
        if (tableNo && cartNotEmpty) {
            checkoutLink.classList.remove("disabled"); // Enable the link
            checkoutLink.href = "payment.php";         // Set the link destination
        } else {
            checkoutLink.classList.add("disabled");    // Disable the link
            checkoutLink.removeAttribute("href");      // Remove the link destination
        }
    });
}

// Function to check if the cart is empty
function checkCartStatus(tableNo, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "orders-code.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Call the callback function with the cart's empty status
            callback(xhr.responseText.trim() === "not_empty");
        }
    };

    xhr.send("checkEmptyCart=1"); // Send a request to check if the cart is empty
}

// Call this function on page load to handle any pre-selected table and update the button accordingly
window.onload = function() {
    updateSummaryLabel(); // This will handle both the label and the button state on page load
}

// AJAX function to add items to cart
function addToCart(menuId) {
    updateCart("addToCart=1&menu_id=" + menuId);
}

// General AJAX function to update cart summary
function updateCart(params) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "orders-code.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("cartSummary").innerHTML = xhr.responseText;
            updateSummaryLabel(); // Update the summary label to reflect changes in the cart
        }
    };

    xhr.send(params);
}

// Function to update item quantity in the cart
function updateQuantity(menuId, action) {
    var params = "updateQuantity=1&menu_id=" + menuId + "&action=" + action;
    updateCart(params);
}
</script>

<?php include('includes/footer.php'); ?>


