<?php
include('includes/header.php');
?>

<div class="container-fluid px-4">
    <div class="row mt-4">
        <!-- Left Card Container (70% width) -->
        <div class="col-lg-8 mb-4" style="color: #d8bc97;">
            <div class="card shadow-sm">
                <div class="card-header" style="background-color: rgba(58, 58, 58);">
                    <h4 class="mb-0">Payment Options
                        <a onclick="window.location.href='index.php?table_no=<?php echo $orderDetails['table_no']; ?>';" class="btn btn-danger float-end">Back</a>
                    </h4>
                </div>

                <!-- Form for Payment Option Selection -->
                <div class="card-body" style="color: #3a3a3a;">
                    <?php alertMessage(); ?>
                    <div class="container-fluid px-4">
                        <h5>Select Your Payment Method:</h5>
                        <form action="payment-code.php" method="POST">
                            <div class="payment-method-container">
                                <div class="payment-method-option">
                                    <input class="form-check-input" type="radio" name="paymentMethod" value="EWALLET" id="ewallet" required>
                                    <label class="payment-method-label" for="ewallet">
                                        <span class="payment-method-radio"><span class="radio-circle"></span></span>
                                        E-Wallet (TNG)
                                        <span class="method-icon">
                                            <img src="assets/img/images.png" alt="E-Wallet Icon" class="payment-icon">
                                        </span>
                                    </label>
                                </div>
                                <div class="payment-method-option">
                                    <input class="form-check-input" type="radio" name="paymentMethod" value="CARD" id="card" required>
                                    <label class="payment-method-label" for="card">
                                        <span class="payment-method-radio"><span class="radio-circle"></span></span>
                                        Debit/Credit Card
                                        <span class="method-icon">
                                            <img src="https://icons-for-free.com/iff/png/512/card+credit+card+debit+card+visa+card+icon-1320184902260183266.png" alt="Card Icon" class="payment-icon">
                                        </span>
                                    </label>
                                </div>
                                <div class="payment-method-option">
                                    <input class="form-check-input" type="radio" name="paymentMethod" value="CASH" id="cash" required>
                                    <label class="payment-method-label" for="cash">
                                        <span class="payment-method-radio"><span class="radio-circle"></span></span>
                                        Cash
                                        <span class="method-icon">
                                            <img src="https://cdn-icons-png.flaticon.com/512/4279/4279596.png" alt="Cash Icon" class="payment-icon">
                                        </span>
                                    </label>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Card Container (30% width) -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header" style="background-color: #d8bc97;color: #3a3a3a">
                    <h4 class="mb-0">Order Summary</h4>
                </div>
                <div class="card-body" style="background-color: rgba(216, 188, 151,0.8);">
                    <div id="cartSummary">
                        <?php
                        // Display cart details from session
                        displayCart();
                        ?>
                    </div>
                </div>
                <div class="card-footer text-center" style="background-color: rgba(216, 188, 151);">
                    <button type="submit" name="makePayment" class="btn btn-success" style="background-color: #3a3a3a; color: #d8bc97; border: none;"> 
                        Make Payment 
                    </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Function to display cart summary and table number
function displayCart() {
    if (isset($_SESSION['menuItems']) && count($_SESSION['menuItems']) > 0) {
        $totalPrice = 0;
        echo "<ul class='list-group'>";

        // Display the table number if it's available
        if (isset($_SESSION['tableNumber'])) {
            echo "<li class='list-group-item'><strong>Table Number: </strong>{$_SESSION['tableNumber']}</li>";
        }

        foreach ($_SESSION['menuItems'] as $itemId => $item) {
            $totalPrice += $item['price'] * $item['quantity'];
            echo "<li class='list-group-item'>
                    <strong>{$item['name']}</strong> - {$item['quantity']} x RM {$item['price']}
                    <span class='float-end'>RM " . number_format($item['price'] * $item['quantity'], 2) . "</span>
                  </li>";
        }
        echo "</ul>";
        // Display total price
        echo "<hr>";
        echo "<div><strong>Total: RM " . number_format($totalPrice, 2) . "</strong></div>";
        
        // Return total price to be used in JS
        return $totalPrice;
    } else {
        echo "<p>Your cart is empty.</p>";
    }
}

?>

<script>
    // Form validation before submission
    document.querySelector("form").addEventListener("submit", function(e) {
        var paymentMethod = document.querySelector('input[name="paymentMethod"]:checked');

        // If no payment method is selected, prevent form submission
        if (!paymentMethod) {
            e.preventDefault();  // Prevent form submission
            alert("Please select a payment method.");
            return false;
        }
    });
</script>

<?php include('includes/footer.php'); ?>
