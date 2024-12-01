<?php 
session_start();
include('includes/header.php');
include('orders-code.php');  // Include orders-related functions

// If not logged in as a guest, redirect to login page
if (!isset($_SESSION['guestLoggedIn']) || $_SESSION['guestLoggedIn'] !== true) {
    header("Location: ../CLogin/index.php");  // Redirect to login page if not a guest
    exit();
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
                    <h4 class="mb-0">Summary (<span id="summaryTableNo"><?= isset($_SESSION['tableNumber']) ? $_SESSION['tableNumber'] : "Select A Table"; ?></span>)</h4>
                </div>
                <div class="card-body" style="background-color: rgba(216, 188, 151,0.8);">
                    <!-- Cart Summary Table -->
                    <div id="cartSummary">
                        <?php displayCart(); // Display the cart content from the session ?>
                    </div>
                </div>
                <div class="card-footer text-center" style="background-color: rgba(216, 188, 151);">
                    <a class="btn btn-success disabled" style="background-color: #3a3a3a; color: #d8bc97; border: none;" id="checkoutButton" href="payment.php?tableNo=<?php echo urlencode($_SESSION['tableNumber']); ?>">
                        CHECKOUT
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Automatically update table number in the summary
window.onload = function() {
    updateSummaryLabel(); // Ensure the table number is displayed on load
}

// Function to update summary and checkout button based on cart status
function updateSummaryLabel() {
    var tableNo = "<?= isset($_SESSION['tableNumber']) ? $_SESSION['tableNumber'] : '' ?>";
    document.getElementById('summaryTableNo').innerText = tableNo ? tableNo : "Select a table";

    var checkoutLink = document.getElementById('checkoutButton');
    // Check if the cart has items and table number is set
    checkCartStatus(function(cartNotEmpty) {
        if (cartNotEmpty) {
            checkoutLink.classList.remove("disabled"); // Enable the link
            checkoutLink.href = "payment.php?tableNo=" + tableNo; // Set the link destination
        } else {
            checkoutLink.classList.add("disabled");    // Disable the link
            checkoutLink.removeAttribute("href");      // Remove the link destination
        }
    });
}

// Function to check if the cart is empty
function checkCartStatus(callback) {
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
