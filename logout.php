<?php  
require 'config/function.php';

if(isset($_SESSION['loggedIn'])){
    // Call the function to log out and clear session
    logoutSession();
    
    // Redirect to login page with success message
    redirect('login.php', 'Logged Out Successfully');
}
?>
