<?php  
require '../config/function.php';

if(isset($_SESSION['guestLoggedIn'])){
    // Call the function to log out and clear session
    unset($_SESSION['guestLoggedIn']);
    // Redirect to login page with success message
    header('Location: ../CLogin/index.php?table_no=' . $_SESSION['table_no']);  // Redirect to the login page
    exit(); 
}
?>
