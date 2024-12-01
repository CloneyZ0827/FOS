<?php

// Check if the user is a guest (no login required for guests)
if (isset($_SESSION['guestLoggedIn']) && $_SESSION['guestLoggedIn'] === true) {
    // Guest is logged in, do nothing or continue with the page load
} 
// If the user is not a guest, redirect to the login page
else {
    header('Location: ../CLogin/index.php');  // Redirect to the login page
    exit();  // Make sure to stop further script execution after the redirect
}
?>
