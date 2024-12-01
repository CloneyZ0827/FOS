<?php  
require '../config/function.php';

if (isset($_SESSION['customerLoggedIn'])) {
    // Check if table_no exists in the customerUser session and unset it
    if (isset($_SESSION['customerUser']['table_no'])) {
        unset($_SESSION['customerUser']['table_no']);
    }

    // Call the function to log out and clear session
    customerLogoutSession();
    
    // Redirect to login page with success message
    redirect('login.php', 'Logged Out Successfully');
}
?>
