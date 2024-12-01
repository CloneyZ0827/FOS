<?php
session_start();  // Start the session to store the table number
include('includes/header.php');

// Get the table number from the URL query string
$table_no = isset($_GET['table_no']) ? $_GET['table_no'] : null;

// If the table number is provided, store it in the session
if ($table_no) {
    $_SESSION['table_no'] = $table_no; // This stores the table number in the session
}

// Check if guest login is triggered
if (isset($_GET['guest_login']) && $_GET['guest_login'] == 'true') {
    // Set a session variable to indicate the user is a guest
    $_SESSION['guestLoggedIn'] = true;

    // Redirect to the guest page
    header('Location: ../Guest/index.php?table_no=' . $table_no);
    exit(); // Ensure no further code is executed after the redirect
}

// Check if the user is already logged in as a guest
$isGuest = isset($_SESSION['guestLoggedIn']) && $_SESSION['guestLoggedIn'] === true;
?>

<!-- Your HTML content for login form goes here -->
<div class="py-5">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="p-5 rounded-4 shadow-lg" style="background-color: rgba(58, 58, 58, 0.9); color: #d8bc97;">
                    <h1 class="mb-4"><strong>Urban Daybreak</strong></h1>
                    
                    <?php if ($table_no): ?>
                        <p>Table No: <?php echo htmlspecialchars($table_no); ?></p>
                    <?php else: ?>
                        <p>Table not found.</p>
                    <?php endif; ?>

                    <!-- If logged in as a guest, show a "You are logged in as guest" message -->
                    <?php if ($isGuest): ?>
                        <p>You are logged in as a guest.</p>
                    <?php else: ?>
                        <!-- Login and Register links if not logged in as a guest -->
                        <a href="login.php?table_no=<?php echo $table_no; ?>" class="btn btn-custom mt-4">Login</a>
                        <a href="register.php?table_no=<?php echo $table_no; ?>" class="btn btn-custom mt-4">Register</a>
                    <?php endif; ?>
                    
                    <!-- Link to trigger guest login -->
                    <?php if (!$isGuest): ?>
                        <a href="?guest_login=true&table_no=<?php echo $table_no; ?>" class="btn btn-custom mt-4">Login as Guest</a>
                    <?php else: ?>
                        <!-- Link to redirect to the guest page if logged in as a guest -->
                        <a href="../Guest/index.php?table_no=<?php echo $table_no; ?>" class="btn btn-custom mt-4">Go to Guest Page</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-custom {
        background-color: #d8bc97;
        color: #3a3a3a;
        border: none;
        font-weight: bold;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .btn-custom:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
        background-color: #3a3a3a;
    }
</style>

<?php include('includes/footer.php'); ?>
